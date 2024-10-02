const tables = [
    "#table1",
    "#table2",
    "#table3",
    "#table4",
    "#table5",
];
const dataTables = [];

tables.forEach((table) => {
    const dataTable = new simpleDatatables.DataTable(
        document.querySelector(table)
    );
    dataTables.push(dataTable);
});
