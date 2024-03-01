// get interactive tr elements
const interactiveTables = document.querySelectorAll('.interactive-t')

const debouncedHover = 
    (table) => {
    table.querySelectorAll('.interactive').forEach(row => {
        if (row.matches(':hover')) {
            row.classList.add("hovered");
        } else {
            row.classList.remove("hovered");
        }
    });
}
//, 200, 1000); // debounce if lagging - as of 09/11/2023 it is working without debounce


const clearInteractiveTable = (table) => {
    table.querySelectorAll('.interactive').forEach(row => {
        // remove hover class on all rows in the table
        if (row.className.includes('hovered')){
        row.classList.remove("hovered");
}}
    )

}



interactiveTables.forEach(table => table.addEventListener('mouseover', () => {debouncedHover(table)}))
interactiveTables.forEach(table => table.addEventListener('mouseleave', () => {
    clearInteractiveTable(table)
}))





