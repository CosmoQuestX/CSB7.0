async function postData(url, data, options) {
    // Default options are marked with *
    let defaultOptions = {
        method: 'POST', // *GET, POST, PUT, DELETE, etc.
        mode: 'cors', // no-cors, *cors, same-origin
        cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
        credentials: 'same-origin', // include, *same-origin, omit
        headers: {
            'Content-Type': 'application/json'
        },
        redirect: 'follow', // manual, *follow, error
        referrerPolicy: 'no-referrer', // no-referrer, *client
        body: JSON.stringify(data) // body data type must match "Content-Type" header
    };
    const response = await fetch(url, { ...defaultOptions, ...options }).then(handleErrors);
    return await response.json(); // parses JSON response into native JavaScript objects
}

async function getData(url, options) {
    // Default options are marked with *
    let defaultOptions = {
        method: 'GET', // *GET, POST, PUT, DELETE, etc.
        mode: 'cors', // no-cors, *cors, same-origin
        credentials: 'same-origin', // include, *same-origin, omit
        redirect: 'follow', // manual, *follow, error
        referrerPolicy: 'no-referrer', // no-referrer, *client
    };
    const response = await fetch(url, { ...defaultOptions, ...options }).then(handleErrors);
    return await response.json(); // parses JSON response into native JavaScript objects
}

function handleErrors(response) {
    if (!response.ok) {
        throw Error(response.statusText);
    }
    return response;
}

function getTimestamp() {
    let dt = new Date(),
        str = `${dt.getFullYear()}-${String(dt.getMonth()).padStart(2, "0")}-${String(dt.getDate()).padStart(2, "0")} ${String(dt.getHours()).padStart(2, "0")}:${String(dt.getMinutes()).padStart(2, "0")}:${String(dt.getSeconds()).padStart(2, "0")}.${String(dt.getMilliseconds()).padStart(3, "0")}`; // [YYYY-MM-DD hh:mm:ss.msc]
    return `[${str}] `;
}
