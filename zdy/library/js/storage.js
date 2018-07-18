+(function (window) {

    Storage = function () {

    };

    Storage.prototype.local = function (key, value) {
        if (key === undefined && value === undefined) {
            let values = {};
            for (let i = 0; i < localStorage.length; i++) {
                let key = localStorage.key(i);
                values[key] = LStorage(key);
            }
            return values;
        }
        if (key === null && value === undefined) {
            return localStorage.clear(key)
        }
        if (value === null) {
            return localStorage.removeItem(key)
        }
        if (value === undefined) {
            let obj = localStorage.getItem(key);
            try {
                obj = JSON.parse(obj);
                if (typeof obj === 'object') {
                    return obj.value;
                }
            } catch (e) {

            }
            return obj;
        }
        return localStorage.setItem(key, JSON.stringify({value: value}));
    };
    Storage.prototype.session = function (key, value) {
        if (key === undefined && value === undefined) {
            let values = {};
            for (let i = 0; i < sessionStorage.length; i++) {
                let key = sessionStorage.key(i);
                values[key] = LStorage(key);
            }
            return values;
        }
        if (key === null && value === undefined) {
            return sessionStorage.clear(key)
        }
        if (value === null) {
            return sessionStorage.removeItem(key)
        }
        if (value === undefined) {
            let obj = sessionStorage.getItem(key);
            try {
                obj = JSON.parse(obj);
                if (typeof obj === 'object') {
                    return obj.value;
                }
            } catch (e) {

            }
            return obj;
        }
        return sessionStorage.setItem(key, JSON.stringify({value: value}))
    };

    window.Storage = new Storage();
})(window);