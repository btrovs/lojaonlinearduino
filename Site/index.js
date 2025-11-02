document.addEventListener('DOMContentLoaded', function() {
    
    // ===================================================
    // ======= LÓGICA DE PROMOÇÃO EXISTENTE (MANTIDA) =======
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
    
    // O código do menu hambúrguer foi removido daqui.
});