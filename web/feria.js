// PHP entrega la fecha como segundos Unix; Date.now() usa milisegundos.
const reloj = document.querySelector('[data-inicio]');
if (reloj) {
    const inicio = Number(reloj.dataset.inicio) * 1000;
    const cifras = reloj.querySelectorAll('dd');

    function actualizar() {
        const falta = Math.max(0, Math.ceil((inicio - Date.now()) / 1000));
        const valores = [
            Math.floor(falta / 86400),
            Math.floor(falta / 3600) % 24,
            Math.floor(falta / 60) % 60,
            falta % 60
        ];
        cifras.forEach((cifra, i) => {
            cifra.textContent = String(valores[i]).padStart(2, '0');
        });
        document.getElementById('estado-cuenta').hidden = falta > 0;
        return falta > 0;
    }

    if (actualizar()) {
        const intervalo = setInterval(() => {
            if (!actualizar()) clearInterval(intervalo);
        }, 1000);
    }
}
