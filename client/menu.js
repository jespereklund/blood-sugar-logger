function insertMenu(node) {
    const links = [
        ["Blodsukker Logger", "index.html"],
        ["Insulin Logger", "insulin_logger.html"],
        ["Insulin Stikplan", "insulin.html"],
        ["Grafer", "graphs.html"],
        ["Log", "log.html"],
        ["Token", "token.html"]
    ];
    
    for (var i=0; i<links.length; i++) {
        var anchor = document.createElement('a')
        anchor.setAttribute('href',links[i][1])
        anchor.innerHTML = links[i][0]
        node.appendChild(anchor)
    }
}