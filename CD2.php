
interface IProduto {
    public function adicionarQuantidade(int $quantidade);
    public function removerQuantidade(int $quantidade);
    public function precisaRepor(): bool;
    public function getNome(): string;
    public function getQuantidade(): int;
}

class Produto implements IProduto {
    private string $nome;
    private int $quantidade;
    private int $estoqueMinimo;

    public function __construct(string $nome, int $quantidade, int $estoqueMinimo) {
        $this->nome = $nome;
        $this->quantidade = $quantidade;
        $this->estoqueMinimo = $estoqueMinimo;
    }

    public function adicionarQuantidade(int $quantidade) {
        $this->quantidade += $quantidade;
    }

    public function removerQuantidade(int $quantidade) {
        if ($this->quantidade >= $quantidade) {
            $this->quantidade -= $quantidade;
        } else {
            throw new Exception("Estoque insuficiente para {$this->nome}.");
        }
    }

    public function precisaRepor(): bool {
        return $this->quantidade < $this->estoqueMinimo;
    }

    public function getNome(): string {
        return $this->nome;
    }

    public function getQuantidade(): int {
        return $this->quantidade;
    }
}

class Estoque {
    private array $produtos = [];

    public function adicionarProduto(IProduto $produto, int $quantidade) {
        if (isset($this->produtos[$produto->getNome()])) {
            $this->produtos[$produto->getNome()]->adicionarQuantidade($quantidade);
        } else {
            $this->produtos[$produto->getNome()] = $produto;
            $this->produtos[$produto->getNome()]->adicionarQuantidade($quantidade);
        }
    }

    public function removerProduto(string $nome, int $quantidade) {
        if (isset($this->produtos[$nome])) {
            $this->produtos[$nome]->removerQuantidade($quantidade);
        } else {
            throw new Exception("Produto {$nome} não encontrado no estoque.");
        }
    }

    public function relatorioEstoque() {
        foreach ($this->produtos as $produto) {
            echo "Produto: {$produto->getNome()}, Quantidade: {$produto->getQuantidade()}";
            if ($produto->precisaRepor()) {
                echo " - Necessita de reposição.\n";
            } else {
                echo "\n";
            }
        }
    }
}

class HistoricoMovimentacao {
    private array $movimentacoes = [];

    public function registrarEntrada(string $produto, int $quantidade) {
        $this->movimentacoes[] = [
            'tipo' => 'Entrada',
            'produto' => $produto,
            'quantidade' => $quantidade,
            'data' => date('Y-m-d H:i:s')
        ];
    }

    public function registrarSaida(string $produto, int $quantidade, string $responsavel, string $destino) {
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

$estoque = new Estoque();
$historico = new HistoricoMovimentacao();

$estoque->adicionarProduto(new Produto("Medicamento A", 100, 20), 100);
$estoque->adicionarProduto(new Produto("Medicamento B", 75, 15), 75);


$estoque->relatorioEstoque();


$historico->registrarEntrada("Medicamento A", 30);


$estoque->removerProduto("Medicamento A", 20);
$historico->registrarSaida("Medicamento A", 20, "João Silva", "Clínica Cidade X");

$historico->exibirMovimentacoes();
