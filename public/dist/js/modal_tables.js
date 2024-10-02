const table = ["#table12"];
const dataTable = [];

table.forEach((table) => {
    const dataTabless = new simpleDatatables.DataTable(
        document.querySelector(table),
        {
            footer: false,
        }
    );
    dataTable.push(dataTabless);
});
