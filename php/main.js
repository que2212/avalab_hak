
document.addEventListener("DOMContentLoaded", () => {
    const currentLocation = window.location.pathname;
    const navItems = document.querySelectorAll(".nav_item");

    navItems.forEach(item => {
        if (item.href.includes(currentLocation)) {
            item.parentElement.classList.add('active');
        }
    });
});


function loadContent(page){
    const contentElement = document.getElementById('main_content');
    const btnGoBack = document.getElementsByClassName('btnGoBack');

    let pageUrl = './recomends.php';
    if (page==='recomends'){
        pageUrl='./recomends.php';

    }
    else if (page==='analytics'){
        pageUrl='./analytics.php';
    }
    else if (page==='chat-bot'){
        pageUrl='./chat-bot.php';
    }
    else{
        contentElement.innerHTML = '<p>Страница не найдена.</p>';
        return;
    }

    fetch(pageUrl)
        .then(response => {
            if(!response.ok){
                throw new console.error('страница в доработке');
            }
            return response.text();
        })

        .then(data => {
            contentElement.innerHTML = data;
            
        })

        .catch(error => {
            console.error(error);
            contentElement.innerHTML = '<p>Ошибка загрузки контента</p>'
        });
}

document.addEventListener('DOMContentLoaded', () => {
    loadContent('recomends');
});


function btnGoBack(){
    btnGoBack.classList.toggle('.active');
}
