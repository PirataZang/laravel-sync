// suas funções
function formatDate(date) {
    if (!date) return '-'
    return new Date(date).toLocaleDateString('pt-BR')
}

function debounce(fn, delay = 300) {
    let timeout
    return (...args) => {
        clearTimeout(timeout)
        timeout = setTimeout(() => {
            fn(...args)
        }, delay)
    }
}

function isMobile() {
    return window.innerWidth <= 768
}

function getInitials(name) {
    if (!name) return ''
    return name
        .split(' ')
        .map(n => n[0])
        .join('')
        .substring(0, 2)
        .toUpperCase()
}

/* 👇 AUTO REGISTRO GLOBAL */
if (typeof window !== 'undefined') {
    window.$app = {
        formatDate,
        debounce,
        isMobile,
        getInitials
    }
}

/* ainda permite importar normalmente */
export {
    formatDate,
    debounce,
    isMobile,
    getInitials
}