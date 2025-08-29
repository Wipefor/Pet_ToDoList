function updateClock() {
   const clockElement = document.getElementById('live-clock');
    if (!clockElement) return;

    const now = new Date();
    const formattedTime =
        `${String(now.getDate()).padStart(2, '0')}-${String(now.getMonth() + 1).padStart(2, '0')}-${String(now.getFullYear()).slice(-0)} ` +
        `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}:${String(now.getSeconds()).padStart(2, '0')}`;

    clockElement.textContent = formattedTime;
}

/*document.addEventListener('DOMContentLoaded', function() {
     updateClock();
     setInterval(updateClock, 1000);
 });*/
//DOM is extra measure witch <script ... defer></script> do just fine
updateClock();
setInterval(updateClock, 1000);