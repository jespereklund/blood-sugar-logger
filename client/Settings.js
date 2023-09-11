class Settings
{
    static token = '';
    static now = true;
    static now_insulin_units = true;
    static BASE_URL = "https://flettedehvaler.dk/blodsukker_logger/server/"

    static load()
    {
        var data = JSON.parse( localStorage.getItem( Settings.Save_ID ));
        if ( !data ) data = {};
        if ( undefined !== data.token ) Settings.token = data.token;
        if ( undefined !== data.now ) Settings.now = data.now;
        if ( undefined !== data.now_insulin_units ) Settings.now_insulin_units = data.now_insulin_units;
    }
    static save()
    {
        var data =
        {
            token: Settings.token,
            now: Settings.now,
            now_insulin_units: Settings.now_insulin_units
        };

        localStorage.setItem( Settings.Save_ID, JSON.stringify( data ));
    }
    static Save_ID = 'Blood_v2';
}