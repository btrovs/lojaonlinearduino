document.addEventListener('DOMContentLoaded', () => {
    setupViewToggle();
    setupFiltersAndSorting();
});

function setupViewToggle() {
    const modoBotoes = document.querySelectorAll('.filtro-esq .modo');
    const containerProdutos = document.querySelector('.produtos');

    if (modoBotoes.length === 0 || !containerProdutos) return;

    modoBotoes.forEach(botao => {
        botao.addEventListener('click', () => {
            modoBotoes.forEach(b => b.classList.remove('ativo'));
            botao.classList.add('ativo');

            const isListView = botao.querySelector('.fa-list') !== null;
            containerProdutos.classList.toggle('list-view', isListView);
        });
    });
}

function setupFiltersAndSorting() {
    const containerProdutos = document.querySelector('.produtos');
    const selectOrdenacao = document.getElementById('ordenacao');
    const selectMostrar = document.getElementById('mostrar');

    if (!containerProdutos || !selectOrdenacao || !selectMostrar) return;

    const produtosArray = Array.from(containerProdutos.querySelectorAll('.card-link'));
    const originalOrder = [...produtosArray];

    const getPreco = (el) => parseFloat(el.dataset.preco) || 0;

    function aplicarFiltrosEOrdenacao() {
        let produtosFiltrados = [...produtosArray];
        const tipoOrdenacao = selectOrdenacao.value;
        const quantidadeMostrar = selectMostrar.value;

        if (tipoOrdenacao === 'menor-preco') {
            produtosFiltrados.sort((a, b) => getPreco(a) - getPreco(b));
        } else if (tipoOrdenacao === 'maior-preco') {
            produtosFiltrados.sort((a, b) => getPreco(b) - getPreco(a));
        } else if (tipoOrdenacao === 'padrao') {
            produtosFiltrados = [...originalOrder];
        }

        containerProdutos.innerHTML = '';
        
        const limite = (quantidadeMostrar === 'todos') ? Infinity : parseInt(quantidadeMostrar);

        produtosFiltrados.forEach((produto, index) => {
            produto.style.display = (index < limite) ? 'block' : 'none';
            containerProdutos.appendChild(produto);
        });
    }

    selectOrdenacao.addEventListener('change', aplicarFiltrosEOrdenacao);
    selectMostrar.addEventListener('change', aplicarFiltrosEOrdenacao);
}
