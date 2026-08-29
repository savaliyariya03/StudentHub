function changeTheme() 
{
    document.body.classList.toggle("dark");
}
function openModal() 
{
    document.getElementById("myModal").style.display = "block";
}
function closeModal() 
{
    document.getElementById("myModal").style.display = "none";
}
function faq()
{
    var btns=document.querySelectorAll('.fag-question');
    var answer=document.querySelectorAll('.fag-answer');
    btns.forEach(function(btn)
    {
        btn.addEventListener("click",function()
        {
            var result=btn.nextElementSibling;
            result.classList.toggle("show");
        })
    })
}
let slide = 1;
function nextSlide() 
{
    slide++;
    if (slide > 3) 
    {
        slide = 1;
    }
    document.getElementById("slideText").innerHTML ="Slide " + slide;
}
function previousSlide() 
{
    slide--;
    if (slide < 1)
    {
        slide = 3;
    }
    document.getElementById("slideText").innerHTML ="Slide " + slide;
}