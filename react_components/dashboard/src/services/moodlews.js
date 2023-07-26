import { useState, useEffect } from "react";

export default class MoodleWs {

    static sesskey = null;
    static wwwroot = null;

    constructor(sesskey, wwwroot){
        this.sesskey = sesskey;
        this.wwwroot = wwwroot;
    }

    callMoodleWS(wsname, args) {

        const [data, setData] = useState(null);

         // POST request using fetch with async/await
        const requestOptions = {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', "sec-fetch-site": "same-origin" },
            body: JSON.stringify(
                [{
                        index: 0,
                        methodname: wsname,
                        args: args
                      }])
        };
        const wscall = this.wwwroot + '/lib/ajax/service.php?sesskey=' + this.sesskey + '&info=' + wsname;
        useEffect(async () => {
              await fetch(wscall, requestOptions)
                 .then(response => response.json())
                 .then(data => setData(data[0].data));
         }, []);
        console.log(data);
        return data;
    }

    getMoodleStrings(args) {
       return this.callMoodleWS('core_get_strings', args);
    }
}