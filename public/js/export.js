window.exportToJPG = function(elementId, filename) {
    const element = document.getElementById(elementId);
    if (!element) return alert('Elemen tidak ditemukan untuk diekspor.');
    
    const now = new Date();
    const timestamp = now.getFullYear() + ('0' + (now.getMonth() + 1)).slice(-2) + ('0' + now.getDate()).slice(-2) + '_' + 
                      ('0' + now.getHours()).slice(-2) + ('0' + now.getMinutes()).slice(-2) + ('0' + now.getSeconds()).slice(-2);
    const finalFilename = filename + '_' + timestamp;
    
    document.querySelectorAll('.export-btn-hide, .no-export').forEach(el => el.classList.add('hidden', '!hidden'));
    document.querySelectorAll('.export-show-text').forEach(el => el.classList.remove('hidden'));
    
    const scrollAreas = element.querySelectorAll('.overflow-x-auto, .overflow-y-auto, .overflow-hidden, [class*="max-h-"]');
    scrollAreas.forEach(el => {
        el.setAttribute('data-orig-overflow', el.style.overflow || '');
        el.setAttribute('data-orig-maxheight', el.style.maxHeight || '');
        el.style.setProperty('overflow', 'visible', 'important');
        el.style.setProperty('max-height', 'none', 'important');
    });
    
    const tables = element.querySelectorAll('table');
    tables.forEach(table => {
        table.classList.remove('border-separate');
        table.classList.add('border-collapse');
    });
    
    const stickies = element.querySelectorAll('.sticky');
    stickies.forEach(st => {
        st.setAttribute('data-orig-position', st.style.position || '');
        st.style.setProperty('position', 'static', 'important');
    });

    // Center table groups
    const groups = element.querySelectorAll('.mb-8');
    groups.forEach(wrap => {
        wrap.setAttribute('data-orig-margin', wrap.style.margin || '');
        wrap.setAttribute('data-orig-width', wrap.style.width || '');
        wrap.style.setProperty('margin', '0 auto', 'important');
        wrap.style.setProperty('width', 'max-content', 'important');
    });

    const fixedParents = [];
    let curr = element;
    while(curr && curr !== document.body) {
        if (window.getComputedStyle(curr).position === 'fixed') {
            fixedParents.push({ el: curr, origPos: curr.style.position || '' });
            curr.style.setProperty('position', 'absolute', 'important');
        }
        curr = curr.parentElement;
    }

    const origWidth = element.style.width;
    element.style.width = element.scrollWidth + 'px'; // Fix width instead of max-content to prevent collapse

    setTimeout(() => {
        const targetWidth = element.scrollWidth;
        html2canvas(element, { 
            scale: 1.5, // Lower scale slightly to prevent canvas size limits on huge tables
            useCORS: true, 
            backgroundColor: '#ffffff',
            windowWidth: targetWidth,
            width: targetWidth,
            logging: true // enable logging to see if it hangs
        }).then(canvas => {
            const MAX_CANVAS_HEIGHT = 2000; 
            
            if (canvas.height > MAX_CANVAS_HEIGHT * 1.5) {
                let currentY = 0;
                let part = 1;
                
                const processPart = () => {
                    if (currentY >= canvas.height) {
                        restoreUI();
                        return;
                    }
                    
                    let sliceHeight = Math.min(MAX_CANVAS_HEIGHT, canvas.height - currentY);
                    let sliceCanvas = document.createElement('canvas');
                    sliceCanvas.width = canvas.width;
                    sliceCanvas.height = sliceHeight;
                    let ctx = sliceCanvas.getContext('2d');
                    ctx.drawImage(canvas, 0, currentY, canvas.width, sliceHeight, 0, 0, canvas.width, sliceHeight);
                    
                    const link = document.createElement('a');
                    link.download = finalFilename + '_Part_' + part + '.jpg';
                    link.href = sliceCanvas.toDataURL('image/jpeg', 0.95);
                    link.click();
                    
                    currentY += sliceHeight;
                    part++;
                    
                    setTimeout(processPart, 300); 
                };
                
                processPart();
                
            } else {
                const link = document.createElement('a');
                link.download = finalFilename + '.jpg';
                link.href = canvas.toDataURL('image/jpeg', 0.95);
                link.click();
                restoreUI();
            }
            
            function restoreUI() {
                document.querySelectorAll('.export-btn-hide, .no-export').forEach(el => el.classList.remove('hidden', '!hidden'));
                document.querySelectorAll('.export-show-text').forEach(el => el.classList.add('hidden'));
                element.style.width = origWidth;
                scrollAreas.forEach(el => {
                    el.style.overflow = el.getAttribute('data-orig-overflow');
                    el.style.maxHeight = el.getAttribute('data-orig-maxheight');
                });
                tables.forEach(table => {
                    table.classList.add('border-separate');
                    table.classList.remove('border-collapse');
                });
                stickies.forEach(st => st.style.position = st.getAttribute('data-orig-position'));
                groups.forEach(wrap => {
                    wrap.style.margin = wrap.getAttribute('data-orig-margin');
                    wrap.style.width = wrap.getAttribute('data-orig-width');
                });
                fixedParents.forEach(fp => fp.el.style.position = fp.origPos);
            }
        }).catch(err => {
            console.error('Export JPG Error:', err);
            alert('Gagal mengekspor JPG: ' + (err.message || err));
            document.querySelectorAll('.export-btn-hide, .no-export').forEach(el => el.classList.remove('hidden', '!hidden'));
            document.querySelectorAll('.export-show-text').forEach(el => el.classList.add('hidden'));
            element.style.width = origWidth;
            fixedParents.forEach(fp => fp.el.style.position = fp.origPos);
        });
    }, 500); // Wait longer for reflows
};

window.exportToPDF = function(elementId, filename) {
    const element = document.getElementById(elementId);
    if (!element) return alert('Elemen tidak ditemukan untuk diekspor.');
    
    const now = new Date();
    const timestamp = now.getFullYear() + ('0' + (now.getMonth() + 1)).slice(-2) + ('0' + now.getDate()).slice(-2) + '_' + 
                      ('0' + now.getHours()).slice(-2) + ('0' + now.getMinutes()).slice(-2) + ('0' + now.getSeconds()).slice(-2);
    const finalFilename = filename + '_' + timestamp;
    
    document.querySelectorAll('.export-btn-hide, .no-export').forEach(el => el.classList.add('hidden', '!hidden'));
    document.querySelectorAll('.export-show-text').forEach(el => el.classList.remove('hidden'));
    
    const scrollAreas = element.querySelectorAll('.overflow-x-auto, .overflow-y-auto, .overflow-hidden, [class*="max-h-"]');
    scrollAreas.forEach(el => {
        el.setAttribute('data-orig-overflow', el.style.overflow || '');
        el.setAttribute('data-orig-maxheight', el.style.maxHeight || '');
        el.style.setProperty('overflow', 'visible', 'important');
        el.style.setProperty('max-height', 'none', 'important');
    });
    
    const tables = element.querySelectorAll('table');
    tables.forEach(table => {
        table.classList.remove('border-separate');
        table.classList.add('border-collapse');
    });
    
    const stickies = element.querySelectorAll('.sticky');
    stickies.forEach(st => {
        st.setAttribute('data-orig-position', st.style.position || '');
        st.style.setProperty('position', 'static', 'important');
    });

    const groups = element.querySelectorAll('.mb-8');
    groups.forEach(wrap => {
        wrap.setAttribute('data-orig-margin', wrap.style.margin || '');
        wrap.setAttribute('data-orig-width', wrap.style.width || '');
        wrap.style.setProperty('margin', '0 auto', 'important');
        wrap.style.setProperty('width', 'max-content', 'important');
    });

    const fixedParents = [];
    let curr = element;
    while(curr && curr !== document.body) {
        if (window.getComputedStyle(curr).position === 'fixed') {
            fixedParents.push({ el: curr, origPos: curr.style.position || '' });
            curr.style.setProperty('position', 'absolute', 'important');
        }
        curr = curr.parentElement;
    }

    const origWidth = element.style.width;
    element.style.width = element.scrollWidth + 'px';
    
    setTimeout(() => {
        const targetWidth = element.scrollWidth;
        html2canvas(element, { 
            scale: 1.5, 
            useCORS: true, 
            backgroundColor: '#ffffff',
            windowWidth: targetWidth,
            width: targetWidth,
            logging: true
        }).then(canvas => {
            const { jsPDF } = window.jspdf || window;
            if (!jsPDF) return alert('Library jsPDF tidak ditemukan.');

            const imgData = canvas.toDataURL('image/jpeg', 0.95);
            
            const pdf = new jsPDF('l', 'mm', 'a4');
            const pdfWidth = pdf.internal.pageSize.getWidth();
            const pdfHeight = pdf.internal.pageSize.getHeight();
            
            const ratio = pdfWidth / canvas.width;
            const scaledHeight = canvas.height * ratio;
            
            let heightLeft = scaledHeight;
            let position = 0;
            
            pdf.addImage(imgData, 'JPEG', 0, position, pdfWidth, scaledHeight);
            heightLeft -= pdfHeight;
            
            while (heightLeft > 0) {
                position = heightLeft - scaledHeight;
                pdf.addPage();
                pdf.addImage(imgData, 'JPEG', 0, position, pdfWidth, scaledHeight);
                heightLeft -= pdfHeight;
            }
            
            pdf.save(finalFilename + '.pdf');
            
            document.querySelectorAll('.export-btn-hide, .no-export').forEach(el => el.classList.remove('hidden', '!hidden'));
            document.querySelectorAll('.export-show-text').forEach(el => el.classList.add('hidden'));
            element.style.width = origWidth;
            scrollAreas.forEach(el => {
                el.style.overflow = el.getAttribute('data-orig-overflow');
                el.style.maxHeight = el.getAttribute('data-orig-maxheight');
            });
            tables.forEach(table => {
                table.classList.add('border-separate');
                table.classList.remove('border-collapse');
            });
            stickies.forEach(st => st.style.position = st.getAttribute('data-orig-position'));
            groups.forEach(wrap => {
                wrap.style.margin = wrap.getAttribute('data-orig-margin');
                wrap.style.width = wrap.getAttribute('data-orig-width');
            });
            fixedParents.forEach(fp => fp.el.style.position = fp.origPos);
        }).catch(err => {
            console.error('Export PDF Error:', err);
            alert('Gagal mengekspor PDF: ' + (err.message || err));
            document.querySelectorAll('.export-btn-hide, .no-export').forEach(el => el.classList.remove('hidden', '!hidden'));
            document.querySelectorAll('.export-show-text').forEach(el => el.classList.add('hidden'));
            element.style.width = origWidth;
            fixedParents.forEach(fp => fp.el.style.position = fp.origPos);
        });
    }, 500);
};

window.exportToExcel = function(elementId, filename) {
    const element = document.getElementById(elementId);
    if (!element) return;
    
    const now = new Date();
    const timestamp = now.getFullYear() + ('0' + (now.getMonth() + 1)).slice(-2) + ('0' + now.getDate()).slice(-2) + '_' + 
                      ('0' + now.getHours()).slice(-2) + ('0' + now.getMinutes()).slice(-2) + ('0' + now.getSeconds()).slice(-2);
    const finalFilename = filename + '_' + timestamp;
    
    const table = element.querySelector('table');
    if (!table) return alert('Data tabel tidak ditemukan untuk diekspor.');
    
    let csv = [];
    const rows = table.querySelectorAll('tr');
    
    for (let i = 0; i < rows.length; i++) {
        let row = [], cols = rows[i].querySelectorAll('td, th');
        
        for (let j = 0; j < cols.length; j++) {
            let col = cols[j];
            let data = col.innerText.replace(/(\r\n|\n|\r)/gm, " ").replace(/\s+/g, ' ').trim();
            
            if (data === "" && col.querySelector('div[title]')) {
                let title = col.querySelector('div[title]').getAttribute('title');
                let val = title.split(':');
                if (val.length > 1) {
                    data = val[1].trim();
                } else {
                    data = title;
                }
            }
            
            data = data.replace(/"/g, '""');
            row.push('"' + data + '"');
        }
        if (row.length > 0 && row.join('') !== '') {
            csv.push(row.join(','));
        }
    }
    
    const csvString = csv.join('\n');
    const blob = new Blob(["\uFEFF" + csvString], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = finalFilename + ".csv";
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
};
