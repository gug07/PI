// Função que captura as respostas da URL
function getQueryParams() {
    const urlParams = new URLSearchParams(window.location.search);
    const tipo = urlParams.get('tipo');
    const volume = urlParams.get('volume');
    const frizz = urlParams.get('frizz');
    return { tipo, volume, frizz };
}

// Função para gerar as recomendações
function generateRecommendation() {
    const { tipo, volume, frizz } = getQueryParams();

    let recommendations = [];

    // Recomendações com base no tipo de cabelo
    if (tipo.includes('Liso')) {
        recommendations = recommendations.concat(getProductsByCategory(4, volume, frizz));
    } else if (tipo.includes('Ondulado')) {
        recommendations = recommendations.concat(getProductsByCategory(5, volume, frizz));
    } else if (tipo.includes('Cacheado')) {
        recommendations = recommendations.concat(getProductsByCategory(6, volume, frizz));
    } else if (tipo.includes('Crespo')) {
        recommendations = recommendations.concat(getProductsByCategory(7, volume, frizz));
    }

    // Recomendações baseadas no volume e no frizz
    if (volume === 'Alto') {
        recommendations = recommendations.concat(getProductsForVolume('Alto'));
    } else if (volume === 'Baixo') {
        recommendations = recommendations.concat(getProductsForVolume('Baixo'));
    }

    if (frizz === 'Sim') {
        recommendations = recommendations.concat(getProductsForFrizz('Sim'));
    } else if (frizz === 'Moderado') {
        recommendations = recommendations.concat(getProductsForFrizz('Moderado'));
    }

    // Exibe as recomendações
    displayRecommendations(recommendations);
}

// Função para obter os produtos baseados na categoria
function getProductsByCategory(categoriaId, volume, frizz) {
    const products = {
        4: [  // Produtos para Cabelos Lisos
            { id: 6, nome: 'Shampoo para Cabelos Lisos', descricao: 'Shampoo que limpa suavemente e realça o brilho dos cabelos lisos.', preco: 29.90, img: 'img/shampoo_lisos.jpg' },
            { id: 7, nome: 'Condicionador para Cabelos Lisos', descricao: 'Condicionador que desembaraça e mantém os cabelos lisos macios e hidratados.', preco: 32.90, img: 'img/condicionador_lisos.jpg' },
            { id: 8, nome: 'Creme de Pentear para Cabelos Lisos', descricao: 'Creme que ajuda a controlar o frizz e melhora a textura dos cabelos lisos.', preco: 24.90, img: 'img/creme_lisos.jpg' },
            { id: 9, nome: 'Óleo Capilar para Cabelos Lisos', descricao: 'Óleo leve que proporciona brilho intenso e proteção contra danos.', preco: 49.90, img: 'img/oleo_lisos.jpg' }
        ],
        5: [  // Produtos para Cabelos Ondulados
            { id: 10, nome: 'Shampoo para Cabelos Ondulados', descricao: 'Shampoo que define as ondas e limpa suavemente os fios.', preco: 29.90, img: 'img/shampoo_ondulados.jpg' },
            { id: 12, nome: 'Condicionador para Cabelos Ondulados', descricao: 'Condicionador que hidrata e melhora a definição das ondas.', preco: 32.90, img: 'img/condicionador_ondulados.jpg' },
            { id: 7, nome: 'Creme de Pentear para Cabelos Ondulados', descricao: 'Creme leve que ativa as ondas e reduz o frizz.', preco: 24.90, img: 'img/creme_ondulados.jpg' },
            { id: 13, nome: 'Óleo Capilar para Cabelos Ondulados', descricao: 'Óleo nutritivo que proporciona maciez e hidratação.', preco: 49.90, img: 'img/oleo_ondulados.jpg' }
        ],
        6: [  // Produtos para Cabelos Cacheados
            { id: 14, nome: 'Shampoo para Cabelos Cacheados', descricao: 'Shampoo que limpa e hidrata profundamente os cabelos cacheados.', preco: 29.90, img: 'img/shampoo_cacheados.jpg' },
            { id: 15, nome: 'Condicionador para Cabelos Cacheados', descricao: 'Condicionador que define os cachos e facilita o desembaraço.', preco: 32.90, img: 'img/condicionador_cacheados.jpg' },
            { id: 16, nome: 'Creme de Pentear para Cabelos Cacheados', descricao: 'Creme que modela os cachos e reduz o volume.', preco: 24.90, img: 'img/creme_cacheados.jpg' },
            { id: 17, nome: 'Óleo Capilar para Cabelos Cacheados', descricao: 'Óleo que nutre e dá brilho aos cabelos cacheados.', preco: 49.90, img: 'img/oleo_cacheados.jpg' }
        ],
        7: [  // Produtos para Cabelos Crespos
            { id: 18, nome: 'Shampoo para Cabelos Crespos', descricao: 'Shampoo que limpa suavemente e preserva a hidratação dos cabelos crespos.', preco: 29.90, img: 'img/shampoo_crespos.jpg' },
            { id: 19, nome: 'Condicionador para Cabelos Crespos', descricao: 'Condicionador que hidrata profundamente e desembaraça os fios.', preco: 32.90, img: 'img/condicionador_crespos.jpg' },
            { id: 20, nome: 'Creme de Pentear para Cabelos Crespos', descricao: 'Creme que proporciona definição e maciez aos cabelos crespos.', preco: 24.90, img: 'img/creme_crespos.jpg' },
            { id: 21, nome: 'Óleo Capilar para Cabelos Crespos', descricao: 'Óleo que nutre intensamente e sela as cutículas.', preco: 49.90, img: 'img/oleo_crespos.jpg' }
        ]
    };

    return products[categoriaId] || [];
}

// Função para obter os produtos com base no volume
function getProductsForVolume(volume) {
    const products = {
        'Alto': [
            { id: 22, nome: 'Shampoo para Cabelos Volumosos', descricao: 'Shampoo que reduz o volume e mantém a hidratação dos fios.', preco: 29.90, img: 'img/shampoo_volumosos.jpg' },
            { id: 24, nome: 'Creme de Pentear para Cabelos Volumosos', descricao: 'Creme que suaviza o volume e proporciona definição aos fios.', preco: 24.90, img: 'img/creme_volumosos.jpg' }
        ],
        'Baixo': [
            { id: 30, nome: 'Shampoo para Cabelos Finos', descricao: 'Shampoo que fortalece e dá volume aos cabelos finos.', preco: 29.90, img: 'img/shampoo_finos.jpg' },
            { id: 32, nome: 'Creme de Pentear para Cabelos Finos', descricao: 'Creme que facilita o penteado e dá volume natural.', preco: 24.90, img: 'img/creme_finos.jpg' }
        ]
    };

    return products[volume] || [];
}

// Função para obter os produtos baseados no frizz
function getProductsForFrizz(frizz) {
    const products = {
        'Sim': [
            { id: 26, nome: 'Shampoo Anti-Frizz', descricao: 'Shampoo que elimina o frizz e deixa os cabelos mais alinhados.', preco: 29.90, img: 'img/shampoo_antifrizz.jpg' },
            { id: 28, nome: 'Creme Anti-Frizz', descricao: 'Creme que controla o frizz e dá brilho.', preco: 24.90, img: 'img/creme_antifrizz.jpg' }
        ],
        'Moderado': [
            { id: 0, nome: 'Shampoo Anti-Frizz Moderado', descricao: 'Shampoo que combate o frizz e hidrata os fios.', preco: 29.90, img: 'img/shampoo_antifrizz_moderado.jpg' },
            { id: 0, nome: 'Creme Anti-Frizz Moderado', descricao: 'Creme que controla o frizz de forma leve.', preco: 24.90, img: 'img/creme_antifrizz_moderado.jpg' }
        ]
    };

    return products[frizz] || [];
}

// Função para exibir as recomendações na tela
function displayRecommendations(recommendations) {
    const recommendationElement = document.getElementById('productRecommendation');
    recommendationElement.innerHTML = ''; // Limpa a área de recomendações

    recommendations.forEach(product => {
        const productDiv = document.createElement('div');
        productDiv.classList.add('product');

        // Cria um link para a página de detalhes do produto
        const productLink = document.createElement('a');
        productLink.href = `detalhes_produto.php?id=${product.id}`;  // Link para detalhes_produto.php com o ID do produto
        productLink.classList.add('product-link');

        // Adiciona o conteúdo do produto dentro do link
        productLink.innerHTML = `
            <img src="${product.img}" alt="${product.nome}" style="max-width: 600px; width: 100%; height: auto;">
            <h3>${product.nome}</h3>
            <p>${product.descricao}</p>
            <p>R$ ${product.preco.toFixed(2)}</p>
        `;
        
        productDiv.appendChild(productLink);
        recommendationElement.appendChild(productDiv);
    });
}


// Gera as recomendações assim que a página for carregada
window.onload = generateRecommendation;
