
function clearForm() {
    note_input.value = ""
    bloodsucker_input.value = ""
}

async function getFingerImage() {
    const url = new URL("getFingerImageNumber.php", Settings.BASE_URL)
    const response = await fetch(url.href)
    const data = await response.json()
    fingerImage.src = "img/fingers/finger_" + data.imageNumber + ".png"
}

async function getLogData() {
    const url = new URL("getLog.php", Settings.BASE_URL)
    const search_params = url.searchParams
    search_params.append("pageFirstResult", 0)
    search_params.append("rowsPerPage", 20)
    search_params.append("direction", "desc")

    const dataset = await $.ajax(url.href)
    
    const table = dataTable
    while (table.rows.length > 1) {
        table.deleteRow(1)
    }
    const col = ['datetime', 'note', 'bloodsucker'];   
    var oldDay = 0
    var currentClass = "bright"
    for (var i = 0; i < dataset.length; i++) {
        var day = (dataset[i][col[0]]).substring(8,10)
        tr = table.insertRow(-1)        
        if (day != oldDay) {
            oldDay = day;
            currentClass = (currentClass == "bright") ? "dark" : "bright"
        } 
        tr.className = currentClass

        dataset[i][col[0]] = (dataset[i][col[0]]).substring(0,16)
        dataset[i][col[2]] = parseFloat((dataset[i][col[2]])).toFixed(1)

        for (var j = 0; j < col.length; j++) {
            var tabCell = tr.insertCell(-1)
            tabCell.innerHTML = dataset[i][col[j]]
            if (j==2) {
                tabCell.className = "right"
            }
        }
    }
}

function init() {
    log_form.addEventListener('submit', submitForm, false)
    Settings.load()
    now_checkbox.checked = Settings.now
    datetime_input.disabled = Settings.now
    getFingerImage()
    getLogData()
    insertMenu(menu_div)
}

function now_clicked(state) {
    Settings.now = state
    Settings.save()
    datetime_input.disabled = state
}

async function postForm(form) {
    var url = new URL("postLog.php", Settings.BASE_URL)
    var data = form.serialize() + "&token=" + Settings.token
    const response = await $.ajax({url: url.href, type: "POST", data: data })
    if ( response == "success") {
        getFingerImage()
        getLogData()
        clearForm()
    } else {
        alert(response)
    }
}

async function skip_clicked() {
    const token = Settings.token;
    const url = new URL("skipFingerSpot.php", Settings.BASE_URL)
    const response = await $.ajax({url: url, type: "POST", data: {token : token}})
    if ( response == "success") {
        getFingerImage()
    } else {
        alert(response)
        return;
    }    
}

function submitForm(e) {
    e.preventDefault()
    bloodsucker_input.value = bloodsucker_input.value.replace(",", ".")
    if (confirm('Er målingen på ' + bloodsucker_input.value + " mM korrekt?" ) === true) {
        const form = $(this)
        postForm(form)
    }
}

function tokenOnInput(value) {
    Settings.token = value
    Settings.save()
}