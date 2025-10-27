document.addEventListener('DOMContentLoaded', () => {
    setupViewToggle();
    setupFiltersAndSorting();
});

/**
 * Alterna o modo de visualização (grade ↔ lista)
 */
function setupViewToggle() {
    const modoBotoes = document.querySelectorAll('.filtro-esq .modo');
    const containerProdutos = document.querySelector('.produtos');

    if (modoBotoes.length === 0 || !containerProdutos) return;

    modoBotoes.forEach(botao => {
        botao.addEventListener('click', () => {
            // Remove a classe "ativo" de todos
            modoBotoes.forEach(b => b.classList.remove('ativo'));
            botao.classList.add('ativo');

            // Define se é visualização em lista
            const isListView = botao.querySelector('.fa-list') !== null;
            containerProdutos.classList.toggle('list-view', isListView);
        });
    });
}

/**
 * Aplica filtros e ordenação
 */
function setupFiltersAndSorting() {
    const containerProdutos = document.querySelector('.produtos');
    const selectOrdenacao = document.getElementById('ordenacao');
    const selectMostrar = document.getElementById('mostrar');

    if (!containerProdutos || !selectOrdenacao || !selectMostrar) return;

    // Array com todos os produtos
    const produtosArray = Array.from(containerProdutos.querySelectorAll('.card-link'));
    const originalOrder = [...produtosArray];

    // Função para obter o preço numérico
    const getPreco = (el) => parseFloat(el.dataset.preco) || 0;

    /**
     * Reaplica os filtros e ordenações quando há mudança
     */
    function aplicarFiltrosEOrdenacao() {
        let produtosFiltrados = [...produtosArray];
        const tipoOrdenacao = selectOrdenacao.value;
        const quantidadeMostrar = selectMostrar.value;

        // Ordenação
        if (tipoOrdenacao === 'menor-preco') {
            produtosFiltrados.sort((a, b) => getPreco(a) - getPreco(b));
        } else if (tipoOrdenacao === 'maior-preco') {
            produtosFiltrados.sort((a, b) => getPreco(b) - getPreco(a));
        } else if (tipoOrdenacao === 'padrao') {
            produtosFiltrados = [...originalOrder];
        }

        // Limpeza do container
        containerProdutos.innerHTML = '';

        // Limite de exibição
        const limite = (quantidadeMostrar === 'todos') ? Infinity : parseInt(quantidadeMostrar);

        produtosFiltrados.forEach((produto, index) => {
            produto.style.display = (index < limite) ? 'block' : 'none';
            containerProdutos.appendChild(produto);
        });
    }

    // Eventos de mudança
    selectOrdenacao.addEventListener('change', aplicarFiltrosEOrdenacao);
    selectMostrar.addEventListener('change', aplicarFiltrosEOrdenacao);
}
