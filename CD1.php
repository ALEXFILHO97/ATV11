
class Item {
    private $nome;
    private $quantidade;
    private $estoqueMinimo;

    public function __construct($nome, $quantidade, $estoqueMinimo) {
        $this->nome = $nome;
        $this->quantidade = $quantidade;
        $this->estoqueMinimo = $estoqueMinimo;
    }

    public function adicionar($quantidade) {
        $this->quantidade += $quantidade;
    }

    public function remover($quantidade) {
        if ($this->quantidade >= $quantidade) {
            $this->quantidade -= $quantidade;
        } else {
            throw new Exception("Quantidade insuficiente para {$this->nome}.");
        }
    }

    public function precisaRepor() {
        return $this->quantidade < $this->estoqueMinimo;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getQuantidade() {
        return $this->quantidade;
    }
}

class ControleEstoque {
    private $itens = [];

    public function adicionarItem($nome, $quantidade, $estoqueMinimo) {
        if (isset($this->itens[$nome])) {
            $this->itens[$nome]->adicionar($quantidade);
        } else {
            $this->itens[$nome] = new Item($nome, $quantidade, $estoqueMinimo);
        }
    }

    public function removerItem($nome, $quantidade) {
        if (isset($this->itens[$nome])) {
            $this->itens[$nome]->remover($quantidade);
        } else {
            throw new Exception("Produto {$nome} não encontrado no estoque.");
        }
    }

    public function relatorioEstoque() {
        foreach ($this->itens as $item) {
            echo "Produto: {$item->getNome()}, Quantidade: {$item->getQuantidade()}";
            if ($item->precisaRepor()) {
                echo " - Necessita de reposição.\n";
            } else {
                echo "\n";
            }
        }
    }
}

class HistoricoMovimentacao {
    private $movimentacoes = [];

    public function registrarEntrada($produto, $quantidade) {
        $this->movimentacoes[] = [
            'tipo' => 'Entrada',
            'produto' => $produto,
            'quantidade' => $quantidade,
            'data' => date('Y-m-d H:i:s')
        ];
    }

    public function registrarSaida($produto, $quantidade, $responsavel, $destino) {
        $this->movimentacoes[] = [
            'tipo' => 'Saída',
            'produto' => $produto,
            'quantidade' => $quantidade,
            'responsavel' => $responsavel,
            'destino' => $destino,
            'data' => date('Y-m-d H:i:s')
        ];
    }

    public function exibirMovimentacoes() {
        foreach ($this->movimentacoes as $movimentacao) {
            echo "{$movimentacao['tipo']}: {$movimentacao['produto']} ({$movimentacao['quantidade']} unidades) - Data: {$movimentacao['data']}";
            if ($movimentacao['tipo'] === 'Saída') {
                echo " - Responsável: {$movimentacao['responsavel']}, Destino: {$movimentacao['destino']}\n";
            } else {
                echo "\n";
            }
        }
    }
}


$estoque = new ControleEstoque();
$historico = new HistoricoMovimentacao();


$estoque->adicionarItem("Medicamento A", 120, 30);
$estoque->adicionarItem("Medicamento B", 50, 15);


$estoque->relatorioEstoque();


$historico->registrarEntrada("Medicamento A", 40);

$historico->relatorioEstoque();
$estoque->removerItem("Medicamento A", 25);
$historico->registrarSaida("Medicamento A", 25, "Maria Oliveira", "Clínica Cidade B");


$historico->exibirMovimentacoes();
