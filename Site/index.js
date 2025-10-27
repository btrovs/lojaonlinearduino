document.addEventListener('DOMContentLoaded', function() {
    
    // ===================================================
    // ======= LÓGICA DE PROMOÇÃO EXISTENTE (PROMOÇÕES) =======
    // ===================================================

    const promoCard = document.getElementById('promo-jumper');
    
    if (promoCard) {
        const precoAntigoEl = promoCard.querySelector('.preco .antigo');
        const precoNovoEl = promoCard.querySelector('.preco .novo');
        const precoContainer = promoCard.querySelector('.preco');

        const precoOriginalHTML = precoContainer.innerHTML;

        if (precoAntigoEl && precoNovoEl && precoContainer) {
            
            function parsePrice(priceText) {
                return parseFloat(priceText.replace('R$', '').trim().replace(',', '.'));
            }

            const precoAntigo = parsePrice(precoAntigoEl.innerText);
            const precoNovo = parsePrice(precoNovoEl.innerText);

            if (!isNaN(precoAntigo) && !isNaN(precoNovo) && precoAntigo > precoNovo) {
                
                const descontoPercentual = ((precoAntigo - precoNovo) / precoAntigo) * 100;
                
                const badge = document.createElement('span');
                badge.className = 'promo-badge';
                badge.innerText = `${descontoPercentual.toFixed(0)}% OFF`; 

                promoCard.prepend(badge);

                const valorEconomia = precoAntigo - precoNovo;

                promoCard.addEventListener('mouseenter', function() {
                    precoContainer.innerHTML = `<span class="economia-aviso">Economize R$${valorEconomia.toFixed(2).replace('.', ',')}!</span>`;
                    badge.style.transform = 'rotate(10deg) scale(1.1)';
                });

                promoCard.addEventListener('mouseleave', function() {
                    precoContainer.innerHTML = precoOriginalHTML;
                    badge.style.transform = 'rotate(10deg) scale(1.0)';
                });
            }
        }
    }

    // ===================================================
    // ======= LÓGICA DO MENU HAMBÚRGUER (FUNCIONAL) =======
    // ===================================================

    const btnHamburguer = document.getElementById('menu-hamburguer');
    const btnFechar = document.getElementById('fechar-menu');
    const menuSidebar = document.getElementById('menu-sidebar');
    const menuOverlay = document.getElementById('menu-overlay');
    const itensComSubmenu = document.querySelectorAll('.item-com-submenu > a');

    // Função que faz o TOGGLE (alterna abrir/fechar)
    function toggleMenu() {
        if (!menuSidebar) return; 

        const isOpened = menuSidebar.classList.contains('aberto');
        
        // Alterna a classe principal do menu
        menuSidebar.classList.toggle('aberto');
        
        // Alterna a classe do overlay (fundo escuro)
        if (menuOverlay) menuOverlay.classList.toggle('visivel');

        // Controla a rolagem do corpo da página
        document.body.style.overflow = isOpened ? '' : 'hidden'; 
        
        // CORREÇÃO: Esconde/Mostra o ícone de hambúrguer principal
        if (btnHamburguer) {
            btnHamburguer.style.visibility = isOpened ? 'visible' : 'hidden';
            btnHamburguer.setAttribute('aria-expanded', isOpened ? 'false' : 'true');
        }
    }

    // --- CONEXÃO DOS BOTÕES ---
    
    // 1. Abrir/Fechar pelo ícone Hambúrguer
    if (btnHamburguer) {
        btnHamburguer.addEventListener('click', toggleMenu);
    }

    // 2. Fechar pelo ícone 'X'
    if (btnFechar) {
        btnFechar.addEventListener('click', toggleMenu);
    }
    
    // 3. Fechar clicando no fundo escuro
    if (menuOverlay) {
        menuOverlay.addEventListener('click', toggleMenu);
    }

    // --- EXPANSÃO DOS SUBMENUS ---
    itensComSubmenu.forEach(itemLink => {
        itemLink.addEventListener('click', function(e) {
            e.preventDefault(); 
            const listItem = itemLink.closest('.item-com-submenu'); 
            if (listItem) listItem.classList.toggle('ativo');
        });
    });

});