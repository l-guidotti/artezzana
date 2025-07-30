
function proceedToCheckout() {
    document.getElementById('loadingOverlay').classList.remove('hidden');

    fetch('../../../app/controllers/FinalizarPedidos.php', {
        method: 'POST'
    })
    .then(response => response.json())
    .then(data => {
        // Esconde o loading e mostra modal de sucesso
        document.getElementById('loadingOverlay').classList.add('hidden');
        document.getElementById('checkoutSuccessModal').classList.remove('hidden');

        // Gera número aleatório de pedido fake
        const pedidoNum = Math.floor(10000 + Math.random() * 90000);
        document.getElementById('orderNumber').innerText = `#${pedidoNum}`;
        
        // Atualiza contador de carrinho
        document.getElementById('cartCount').innerText = '0 item(s)';
    })
    .catch(err => {
        console.error('Erro ao finalizar pedido:', err);
        alert('Erro ao finalizar pedido. Tente novamente.');
        document.getElementById('loadingOverlay').classList.add('hidden');
    });
}
