<?php
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <title>CHECKER PAYPAL</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
    :root {
        --primary-color: #ff6b35;
        --secondary-color: #1a1a2e;
        --accent-color: #16213e;
        --success-color: #28a745;
        --danger-color: #dc3545;
        --warning-color: #ffc107;
        --info-color: #17a2b8;
        --dark-bg: #0f0f23;
        --card-bg: #1a1a2e;
        --text-light: #ffffff;
        --text-muted: #b8b9ba;
        --border-color: #2d2d44;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', sans-serif;
        background: linear-gradient(135deg, var(--dark-bg) 0%, var(--secondary-color) 100%);
        color: var(--text-light);
        min-height: 100vh;
        overflow-x: hidden;
    }

    .main-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .header-card {
        background: linear-gradient(135deg, var(--card-bg) 0%, var(--accent-color) 100%);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        position: relative;
        overflow: hidden;
    }

    .header-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-color), var(--info-color), var(--success-color));
    }

    .header-title {
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, var(--primary-color), var(--info-color));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 10px;
        text-align: center;
    }

    .header-subtitle {
        color: var(--text-muted);
        text-align: center;
        font-size: 1.1rem;
        margin-bottom: 30px;
    }

    .control-panel {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .btn-custom {
        padding: 12px 25px;
        border-radius: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        border: none;
        margin: 5px;
        position: relative;
        overflow: hidden;
    }

    .btn-custom::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .btn-custom:hover::before {
        left: 100%;
    }

    .btn-start {
        background: linear-gradient(135deg, var(--success-color), #20c997);
        color: white;
    }

    .btn-pause {
        background: linear-gradient(135deg, var(--warning-color), #fd7e14);
        color: white;
    }

    .btn-stop {
        background: linear-gradient(135deg, var(--danger-color), #e74c3c);
        color: white;
    }

    .btn-clean {
        background: linear-gradient(135deg, var(--info-color), #3498db);
        color: white;
    }

    .status-badge {
        padding: 10px 20px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 1rem;
        display: inline-block;
        margin-top: 15px;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
        100% {
            transform: scale(1);
        }
    }

    .stats-container {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .stat-item {
        background: var(--accent-color);
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        margin: 10px;
        border: 1px solid var(--border-color);
        transition: transform 0.3s ease;
    }

    .stat-item:hover {
        transform: translateY(-5px);
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .stat-label {
        color: var(--text-muted);
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .input-area {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .custom-textarea {
        background: var(--accent-color);
        color: var(--text-light);
        border: 1px solid var(--border-color);
        border-radius: 5px;
        padding: 10px;
        font-family: 'Courier New', monospace;
        font-size: 12px;
        line-height: 1.5;
        resize: vertical;
        transition: border-color 0.3s ease;
        width: 100%;
        min-height: 40px;
    }

    .custom-textarea:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
    }

    .results-tabs {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .nav-tabs {
        background: var(--accent-color);
        border: none;
        padding: 0;
    }

    .nav-tabs .nav-link {
        background: transparent;
        border: none;
        color: var(--text-muted);
        padding: 20px 25px;
        font-weight: 600;
        transition: all 0.3s ease;
        position: relative;
    }

    .nav-tabs .nav-link.active {
        background: var(--card-bg);
        color: var(--text-light);
    }

    .nav-tabs .nav-link::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 0;
        height: 3px;
        background: var(--primary-color);
        transition: width 0.3s ease;
    }

    .nav-tabs .nav-link.active::after {
        width: 100%;
    }

    .tab-content {
        padding: 25px;
        background: var(--card-bg);
    }

    .result-item {
        background: var(--accent-color);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
        font-family: 'Courier New', monospace;
        font-size: 14px;
        word-break: break-all;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .result-success {
        border-left: 4px solid var(--success-color);
        background: rgba(40, 167, 69, 0.1);
    }

    .result-error {
        border-left: 4px solid var(--danger-color);
        background: rgba(220, 53, 69, 0.1);
    }

    .result-warning {
        border-left: 4px solid var(--warning-color);
        background: rgba(255, 193, 7, 0.1);
    }

    .action-buttons {
        margin-bottom: 20px;
    }

    .btn-action {
        background: var(--accent-color);
        border: 1px solid var(--border-color);
        color: var(--text-light);
        padding: 10px 15px;
        border-radius: 8px;
        margin-right: 10px;
        transition: all 0.3s ease;
    }

    .btn-action:hover {
        background: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
    }

    .loading-spinner {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top-color: var(--primary-color);
        animation: spin 1s ease-in-out infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    @media (max-width: 768px) {
        .header-title {
            font-size: 2rem;
        }
        .main-container {
            padding: 15px;
        }
        .stat-item {
            margin: 5px;
        }
    }
    </style>
</head>

<body>
    <div class="main-container">
        <div class="header-card">
            <h1 class="header-title"><i class="fas fa-credit-card"></i> CHECKER PAYPAL <i class="fas fa-credit-card"></i> </h1>
            <p class="header-subtitle"><i class="fas fa-dragon"></i> CONHECIMENTO NÃO É CRIME <i class="fas fa-dragon"></i></p>

            <div class="text-center">
                <button class="btn btn-custom btn-start" id="chk-start">
                    <i class="fas fa-play"></i> Iniciar
                </button>
                <button class="btn btn-custom btn-pause" id="chk-pause" disabled>
                    <i class="fas fa-pause"></i> Pausar
                </button>
                <button class="btn btn-custom btn-stop" id="chk-stop" disabled>
                    <i class="fas fa-stop"></i> Parar
                </button>
                <button class="btn btn-custom btn-clean" id="chk-clean">
                    <i class="fas fa-trash-alt"></i> Limpar
                </button>
            </div>

            <div class="text-center">
                <span class="status-badge badge bg-warning" id="estatus">
                    <i class="fas fa-clock"></i> Aguardando início...
                </span>
            </div>
        </div>

        <div class="stats-container">
            <div class="row">
                <div class="col-md-2 col-6">
                    <div class="stat-item">
                        <div class="stat-number text-success val-lives">0</div>
                        <div class="stat-label">Aprovadas</div>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="stat-item">
                        <div class="stat-number text-danger val-dies">0</div>
                        <div class="stat-label">Reprovadas</div>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="stat-item">
                        <div class="stat-number text-warning val-errors">0</div>
                        <div class="stat-label">Erros</div>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="stat-item">
                        <div class="stat-number text-info val-tested">0</div>
                        <div class="stat-label">Testadas</div>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="stat-item">
                        <div class="stat-number text-primary val-total">0</div>
                        <div class="stat-label">Total</div>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="stat-item">
                        <div class="stat-number text-light" id="progress-percent">0%</div>
                        <div class="stat-label">Progresso</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="input-area">
            <h5 class="mb-3">
                <i class="fas fa-list"></i> LISTA DE CARTÕES CC|MES|ANO|CVV
            </h5>
            <textarea id="lista_Cartões" class="custom-textarea"
                placeholder="CC|MÊS|ANO|CVV"
                rows="12"></textarea>
            <small class="text-muted">
                <i class="fas fa-info-circle"></i> Insira os dados no formato: CC|MÊS|ANO|CVV
            </small>
        </div>

        <div class="results-tabs">
            <ul class="nav nav-tabs" id="resultTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="lives-tab" data-bs-toggle="tab" data-bs-target="#lives-content"
                        type="button" role="tab">
                        <i class="fas fa-check-circle text-success"></i> Aprovadas (<span class="val-lives">0</span>)
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="dies-tab" data-bs-toggle="tab" data-bs-target="#dies-content"
                        type="button" role="tab">
                        <i class="fas fa-times-circle text-danger"></i> Reprovadas (<span class="val-dies">0</span>)
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="errors-tab" data-bs-toggle="tab" data-bs-target="#errors-content"
                        type="button" role="tab">
                        <i class="fas fa-exclamation-triangle text-warning"></i> Erros (<span class="val-errors">0</span>)
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="resultTabContent">
                <div class="tab-pane fade show active" id="lives-content" role="tabpanel">
                    <div class="action-buttons">
                        <button class="btn btn-action" id="copy-lives">
                            <i class="fas fa-copy"></i> Copiar
                        </button>
                        <button class="btn btn-action" id="clear-lives">
                            <i class="fas fa-trash"></i> Limpar
                        </button>
                        <button class="btn btn-action" id="download-lives">
                            <i class="fas fa-download"></i> Download
                        </button>
                    </div>
                    <div id="lives-results" class="results-container"></div>
                </div>

                <div class="tab-pane fade" id="dies-content" role="tabpanel">
                    <div class="action-buttons">
                        <button class="btn btn-action" id="clear-dies">
                            <i class="fas fa-trash"></i> Limpar
                        </button>
                    </div>
                    <div id="dies-results" class="results-container"></div>
                </div>

                <div class="tab-pane fade" id="errors-content" role="tabpanel">
                    <div class="action-buttons">
                        <button class="btn btn-action" id="clear-errors">
                            <i class="fas fa-trash"></i> Limpar
                        </button>
                    </div>
                    <div id="errors-results" class="results-container"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

    <script>
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    $(document).ready(function() {
        let total = 0;
        let tested = 0;
        let lives = 0;
        let dies = 0;
        let errors = 0;
        let stopped = true;
        let paused = false;
        let livesData = [];
        let diesData = [];
        let errorsData = [];

        const audio = new Audio('live.mp3');

        function removeLinha() {
            const lines = $("#lista_Cartões").val().split('\n');
            lines.splice(0, 1);
            $("#lista_Cartões").val(lines.join("\n"));
        }

        function atualizarStats() {
            $(".val-total").text(total);
            $(".val-lives").text(lives);
            $(".val-dies").text(dies);
            $(".val-errors").text(errors);
            $(".val-tested").text(tested);

            const progress = total > 0 ? Math.round((tested / total) * 100) : 0;
            $("#progress-percent").text(progress + "%");

            $("#lives-tab .val-lives").text(lives);
            $("#dies-tab .val-dies").text(dies);
            $("#errors-tab .val-errors").text(errors);
        }

        function adicionarResultado(tipo, conteudo, resposta) {
            if (tipo === 'success') {
                $("#lives-results").prepend(`<div class="result-item result-success">${resposta}</div>`);
                livesData.unshift(conteudo);
                lives++;
            } else if (tipo === 'error') {
                $("#dies-results").prepend(`<div class="result-item result-error">${resposta}</div>`);
                diesData.unshift(conteudo + " -> " + resposta);
                dies++;
            } else {
                $("#errors-results").prepend(`<div class="result-item result-warning">${resposta}</div>`);
                errorsData.unshift(conteudo + " -> " + resposta);
                errors++;
            }
            atualizarStats();
        }

        function testar(lista) {
            if (stopped || paused) return;

            if (tested >= total) {
                $("#estatus").attr("class", "status-badge badge bg-success")
                    .html('<i class="fas fa-check"></i> Teste finalizado!');
                toastr.success(`Teste de ${total} contas finalizado!`);

                $("#chk-start").removeAttr('disabled');
                $("#chk-clean").removeAttr('disabled');
                $("#chk-stop, #chk-pause").attr("disabled", true);
                return;
            }

            const conteudo = lista[tested];

            $("#estatus").attr("class", "status-badge badge bg-info")
                .html(`<span class="loading-spinner"></span> Testando: ${conteudo}`);

            $.ajax({
                url: 'api.php',
                type: 'GET',
                data: {
                    lista: conteudo
                }
            })
            .done(function(response) {
                if (stopped || paused) return;

                tested++;
                const resposta = response.trim();

                // CORREÇÃO: Verifica se é LIVE de forma mais precisa
                // LIVE = contém "✅ LIVE" OU contém "RISK_DISALLOWED" OU contém "INVALID_SECURITY_CODE" OU contém "EXISTING_ACCOUNT_RESTRICTED" OU contém "3DS_REQUIRED" OU contém "APPROVED"
                const isLive = resposta.includes('✅ LIVE') || 
                               resposta.includes('RISK_DISALLOWED') || 
                               resposta.includes('INVALID_SECURITY_CODE') || 
                               resposta.includes('EXISTING_ACCOUNT_RESTRICTED') || 
                               resposta.includes('3DS_REQUIRED') || 
                               resposta.includes('APPROVED') ||
                               resposta.includes('INVALID_BILLING_ADDRESS');

                const isDie = resposta.includes('❌ DIE');

                if (isLive) {
                    $("#estatus").attr("class", "status-badge badge bg-success")
                        .html('<i class="fas fa-check"></i> Aprovada: ' + conteudo);
                    toastr.success("Aprovada: " + conteudo);
                    adicionarResultado('success', conteudo, resposta);
                    audio.play().catch(() => {});
                } else if (isDie) {
                    $("#estatus").attr("class", "status-badge badge bg-danger")
                        .html('<i class="fas fa-times"></i> Reprovada: ' + conteudo);
                    toastr.error("Reprovada: " + conteudo);
                    adicionarResultado('error', conteudo, resposta);
                } else {
                    $("#estatus").attr("class", "status-badge badge bg-warning")
                        .html('<i class="fas fa-exclamation-triangle"></i> Erro: ' + conteudo);
                    toastr.warning("Erro: " + conteudo);
                    adicionarResultado('warning', conteudo, resposta);
                }

                removeLinha();
                setTimeout(() => testar(lista), 1000);
            })
            .fail(function(xhr, status, error) {
                if (stopped || paused) return;

                tested++;
                errors++;

                const errorMsg = `Erro de conexão: ${status} - ${error}`;
                $("#estatus").attr("class", "status-badge badge bg-warning")
                    .html('<i class="fas fa-exclamation-triangle"></i> Erro: ' + conteudo);
                toastr.warning("Erro: " + conteudo + " - " + errorMsg);
                adicionarResultado('warning', conteudo, errorMsg);

                removeLinha();
                setTimeout(() => testar(lista), 2000);
            });
        }

        $("#chk-start").click(function() {
            const lista = $("#lista_Cartões").val().trim().split('\n').filter(line => line.trim());

            if (lista.length === 0) {
                $("#lista_Cartões").focus();
                toastr.warning("Insira Pelo Menos Um Cartão");
                return;
            }

            const invalidLines = lista.filter(line => !line.includes('|') || line.split('|').length !== 4);
            if (invalidLines.length > 0) {
                toastr.error(`Formato inválido na linha: "${invalidLines[0]}". Use: CC|MÊS|ANO|CVV`);
                return;
            }

            total = lista.length;
            tested = 0;
            lives = 0;
            dies = 0;
            errors = 0;
            stopped = false;
            paused = false;

            atualizarStats();

            toastr.success(`Checker Iniciado Com ${total} Cartões`);

            $("#chk-start, #chk-clean").attr("disabled", true);
            $("#chk-stop, #chk-pause").removeAttr('disabled');

            testar(lista);
        });

        $("#chk-pause").click(function() {
            paused = true;
            $("#chk-start").removeAttr('disabled');
            $("#chk-pause").attr("disabled", true);
            $("#estatus").attr("class", "status-badge badge bg-warning")
                .html('<i class="fas fa-pause"></i> Checker pausado');
            toastr.info("Checker pausado!");
        });

        $("#chk-stop").click(function() {
            stopped = true;
            $("#chk-start, #chk-clean").removeAttr('disabled');
            $("#chk-stop, #chk-pause").attr("disabled", true);
            $("#estatus").attr("class", "status-badge badge bg-secondary")
                .html('<i class="fas fa-stop"></i> Checker parado');
            toastr.info("Checker parado!");
        });

        $("#chk-clean").click(function() {
            total = tested = lives = dies = errors = 0;
            stopped = true;
            paused = false;
            livesData = [];
            diesData = [];
            errorsData = [];

            atualizarStats();
            $("#lista_Cartões").val("");
            $("#lives-results, #dies-results, #errors-results").empty();
            $("#estatus").attr("class", "status-badge badge bg-warning")
                .html('<i class="fas fa-clock"></i> Aguardando início...');
            toastr.info("Checker limpo!");
        });

        $("#copy-lives").click(function() {
            if (livesData.length === 0) {
                toastr.warning("Nenhum Cartao Para Copiar");
                return;
            }

            const text = livesData.join('\n');
            navigator.clipboard.writeText(text).then(() => {
                toastr.success("Cartões Copiados!");
            }).catch(() => {
                toastr.error("Erro ao copiar!");
            });
        });

        $("#download-lives").click(function() {
            if (livesData.length === 0) {
                toastr.warning("Nenhum Cartão Para Copiar Em Dawloard");
                return;
            }

            const text = livesData.join('\n');
            const blob = new Blob([text], { type: 'text/plain;charset=utf-8' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `kabum_lives_${new Date().toISOString().slice(0, 10)}.txt`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
            toastr.success("Download iniciado!");
        });

        $("#clear-lives").click(() => {
            $("#lives-results").empty();
            $(".val-lives").text(0);
            lives = 0;
            livesData = [];
            toastr.info("Resultados aprovados limpos!");
        });

        $("#clear-dies").click(() => {
            $("#dies-results").empty();
            $(".val-dies").text(0);
            dies = 0;
            diesData = [];
            toastr.info("Resultados reprovados limpos!");
        });

        $("#clear-errors").click(() => {
            $("#errors-results").empty();
            $(".val-errors").text(0);
            errors = 0;
            errorsData = [];
            toastr.info("Resultados de erro limpos!");
        });
    });
    </script>
</body>

</html>