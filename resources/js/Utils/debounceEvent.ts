let time: any = null;

const debounceEvent = (fn: any, wait = 400) => {
    clearTimeout(time);
    
    time = setTimeout(() => {
        fn();
    }, wait);
};

export default debounceEvent;
