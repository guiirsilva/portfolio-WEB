document.addEventListener('DOMContentLoaded', () => {
    console.log('O site foi carregado e está pronto!');
    
    const secoes = document.querySelectorAll('.secao');
    window.addEventListener('scroll', () => {
        secoes.forEach(secao => {
            const secaoTopo = secao.getBoundingClientRect().top;
            if (secaoTopo < window.innerHeight) {
                secao.style.opacity = '1';
                secao.style.transform = 'translateY(0)';
            }
        });
    });
});
