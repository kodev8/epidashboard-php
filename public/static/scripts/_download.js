//  download tables
const downloads = document.querySelectorAll('#download')
const spreadsheet = document.getElementById('spreadsheet')
const table_check = document.querySelector('table')

function downloadTables () { 


    try {

        if (!table_check) { 
            return 
        }

        else if (!downloads) { 
            throw Error(`No Download Button Found`)

        }else if (!spreadsheet) {
            throw Error(`No Title  Found`)

        } 
                    
        var tables = document.querySelectorAll('table.can-export')
        var wb = XLSX.utils.book_new();

        // filenames are content in section header
        // sheet names are id of of tables
        
        tables.forEach(table => {
        var ws = XLSX.utils.table_to_sheet(table, { raw: true });
        wb.SheetNames.push(table.id)
        wb.Sheets[table.id] = ws
        
        })        

        filename = spreadsheet.dataset.title + '.xlsx'
        XLSX.writeFile(wb, filename);
        createToast('success', filename + ' ' + 'Downloaded Successfully')

    }catch (err) {
        createToast('error', 'Failed to Download Table!')
    }



}
downloads.forEach(download => download.addEventListener('click', downloadTables))