window.showSaveButton = function (id) {
    var select = document.getElementById("status-" + id);
    var button = document.getElementById("save-button-" + id);
    if (select.value !== select.defaultValue) {
        button.style.display = "block";
    } else {
        button.style.display = "none";
    }
};

window.hideSaveButton = function (id) {
    var button = document.getElementById("save-button-" + id);
    button.style.display = "none";
};

window.atualizarStatus = function (id) {
    var status = document.getElementById("status-" + id).value;
    axios
        .patch("/solicitations/" + id, { status: status })
        .then(function (response) {
            hideSaveButton(id);
            window.location.href = "/solicitations";
        })
        .catch(function (error) {
            console.log(error);
        });
};

let currentOrderBy = "created_at";
let currentOrderDirection = "desc";

window.sortTable = function (column) {
    const table = document.getElementById("solicitations-table");
    const tbody = table.querySelector("tbody");
    const rows = Array.from(tbody.querySelectorAll("tr"));

    // Determine the new order direction
    let newOrderDirection = "asc";
    if (currentOrderBy === column && currentOrderDirection === "asc") {
        newOrderDirection = "desc";
    }

    // Sort the rows
    rows.sort((a, b) => {
        const aValue = a.dataset[column];
        const bValue = b.dataset[column];

        let comparison = 0;

        if (column === "created_at") {
            comparison = parseInt(aValue) - parseInt(bValue);
        } else {
            comparison = aValue.localeCompare(bValue);
        }

        return newOrderDirection === "asc" ? comparison : -comparison;
    });

    // Update the table
    rows.forEach((row) => tbody.appendChild(row));

    // Update the current order
    currentOrderBy = column;
    currentOrderDirection = newOrderDirection;

    // Update the sort icons
    updateSortIcons();
};

window.updateSortIcons = function () {
    const icons = document.querySelectorAll('i[id$="-sort-icon"]');

    icons.forEach((icon) => {
        icon.classList.remove("fa-sort-up", "fa-sort-down");
        icon.classList.add("fa-sort");
    });

    const currentIcon = document.getElementById(currentOrderBy + "-sort-icon");
    if (currentIcon) {
        currentIcon.classList.remove("fa-sort");
        currentIcon.classList.add(
            currentOrderDirection === "asc" ? "fa-sort-up" : "fa-sort-down"
        );
    }
};

updateSortIcons();
