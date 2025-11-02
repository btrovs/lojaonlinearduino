// === FUNÇÃO PRINCIPAL ===
document.addEventListener("DOMContentLoaded", () => {
    // Apenas a função de filtros e ordenação deve ser chamada aqui.
    setupFiltersAndSorting();
    
    // NOTA: setupMenuHamburguer() e setupViewToggle() foram removidas para 
    // garantir que o menu seja controlado APENAS pelo index.js (agora vazio de menu)
    // ou que não haja lógica duplicada.
});


/**
 * ============================================================\
 * FILTROS E ORDENAÇÃO
 * Lida com as seleções de ordenação (preço) e exibição (mostrar 10/20/todos).
 * ============================================================\
 */
function setupFiltersAndSorting() {
    const container = document.querySelector(".produtos");
    const selectOrdenacao = document.getElementById("ordenacao");
    const selectMostrar = document.getElementById("mostrar");

    if (!container || !selectOrdenacao || !selectMostrar) return;

    // Seleciona todos os cards de produto (div.card), que agora contém data-preco
    const produtosArray = Array.from(container.querySelectorAll(".card"));
    
    // Cria uma cópia da ordem original (baseada no DOM)
    const ordemOriginal = [...produtosArray];

    // Função auxiliar para obter o preço do data-preco no elemento .card
    const getPreco = el => parseFloat(el.dataset.preco) || 0;


    function aplicarFiltrosEOrdenacao() {
        let filtrados = [...produtosArray];
        const tipoOrdenacao = selectOrdenacao.value;
        const quantidadeMostrar = selectMostrar.value;

        // 1. ORDENAÇÃO
        if (tipoOrdenacao === "menor-preco") {
            filtrados.sort((a, b) => getPreco(a) - getPreco(b));
        } else if (tipoOrdenacao === "maior-preco") {
            filtrados.sort((a, b) => getPreco(b) - getPreco(a));
        } else {
            // Ordenação Padrão: usa a ordem do array original
            filtrados = [...ordemOriginal];
        }

        // 2. LIMPA E REINJETA OS ELEMENTOS NA NOVA ORDEM
        // Limpa o container
        container.innerHTML = "";
        
        // Determina o limite de exibição
        const limite = quantidadeMostrar === "todos" ? Infinity : parseInt(quantidadeMostrar);

        // 3. EXIBIÇÃO E PAGINAÇÃO BÁSICA
        filtrados.forEach((produto, index) => {
            // Controla quais produtos serão exibidos com base na seleção "Mostrar"
            produto.style.display = index < limite ? "block" : "none";
            
            // Adiciona o produto de volta ao container na nova ordem
            container.appendChild(produto);
        });
    }

    // Adiciona os event listeners
    selectOrdenacao.addEventListener("change", aplicarFiltrosEOrdenacao);
    selectMostrar.addEventListener("change", aplicarFiltrosEOrdenacao);
    
    // Aplica a ordenação inicial para garantir que o estado inicial (padrão) seja injetado corretamente
    aplicarFiltrosEOrdenacao();
}