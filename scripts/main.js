let search = document.getElementById('search');
search.addEventListener('click', function (e){
    e.preventDefault();
    let res = document.querySelector('.result');
    fetch('sl.php', {
        method: "post",
        body: new FormData(document.getElementById('form-get-links')),
    }).then(
        response => {
            return response.text();
        }
    ).then(
        text => {
            res.innerHTML = text;
            applyStyles();
        }
    );
});

let clear = document.getElementById('clear');
clear.addEventListener('click', function (){
    let link = document.getElementById('link');
    link.value = '';
});

function applyStyles(){
    $('ul').each(function(){
        $this = $(this);
        $this.find("li").has("ul").addClass("hasSubmenu");
    });
    $('li:last-child').each(function(){
        $this = $(this);
        if ($this.children('ul').length === 0){
            $this.closest('ul').css("border-left", "1px solid gray");
        }
        else {
            $this.closest('ul').children("li").not(":last").css("border-left","1px solid gray");
            $this.closest('ul').children("li").last().children("a").addClass("addBorderBefore");
            $this.closest('ul').css("margin-top","20px");
            $this.closest('ul').find("li").children("ul").css("margin-top","20px");
        }
    });
    $('ul li.hasSubmenu').each(function(){
        $this = $(this);
        $this.prepend("<a href='#'><i class='fa fa-minus-circle fa-2x'></i><i style='display:none;' class='fa fa-plus-circle fa-2x'></i></a>");
        $this.children("a").not(":last").removeClass().addClass("toogle");
    });
    $('ul li.hasSubmenu a.toogle').click(function(){
        $this = $(this);
        $this.closest("li").children("ul").toggle(300);
        $this.children("i").toggle();
        return false;
    });
}