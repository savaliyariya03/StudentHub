let data = [];
let page = 0;

fetch("data/events.json")
.then(response => response.json())
.then(result => {
    data = result;
    display();
})
.catch(() => {
    document.getElementById("output").innerHTML = "Error loading JSON";
});

function display() {
    let result = data;

    let search = document.getElementById("search").value.toLowerCase();
    result = result.filter(x => x.name.toLowerCase().includes(search));

    let filter = document.getElementById("filter").value;
    if (filter != "All") {
        result = result.filter(x => x.category == filter);
    }

    let start = page * 5;
    let list = result.slice(start, start + 5);

    document.getElementById("output").innerHTML = list.map(x =>
        "<article><h3>" + x.name + "</h3>" +
        "<p>Category: " + x.category + "</p>" +
        "<p>Date: " + x.date + "</p></article>"
    ).join("");
}

function sortData() {
    data.sort((a, b) => a.name.localeCompare(b.name));
    display();
}

function next() {
    page++;
    display();
}

function previous() {
    if (page > 0) page--;
    display();
}

document.getElementById("search").onkeyup = display;
document.getElementById("filter").onchange = display;
