document.addEventListener('DOMContentLoaded', () => {
    window.openTab = function(evt, tabName) {
        let i, tabcontent, tablinks;
        
        tabcontent = document.getElementsByClassName("rpg-tab-content");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
            tabcontent[i].classList.remove("active");
        }
        
        tablinks = document.getElementsByClassName("rpg-tab");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].classList.remove("active");
        }
        
        const targetTab = document.getElementById(tabName);
        if (targetTab) {
            targetTab.style.display = "block";
            targetTab.classList.add("active");
        }
        
        if (evt && evt.currentTarget) {
            evt.currentTarget.classList.add("active");
        }
    };
});
