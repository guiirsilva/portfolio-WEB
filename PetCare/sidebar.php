<?php
$menuItems = [
    ["perfil.php", "imgs/icones/user.png", "Perfil"],
    ["alimentacao.php", "imgs/icones/racao.png", "Alimentação"],
    ["higiene.php", "imgs/icones/cachorro-banho.png", "Higiene"],
    ["exercicio.php", "imgs/icones/gato-exercicio.png", "Exercícios"],
    ["vacinas.php", "imgs/icones/vacina.png", "Vacinas"],
    ["controleparasitario.php", "imgs/icones/controle-parasita.png", "Controle Parasitário"],
    ["index.php", "imgs/icones/sair.png", "Logoff", "logout"]
];

$currentUrl = basename($_SERVER['REQUEST_URI']); // Pega o nome do arquivo atual
?>

<div class="sidebar">
    <div class="logo">
        <img src="imgs/logo/logo_petcare.png" alt="Logo">
    </div>
    <ul class="nav flex-column">
        <?php
        foreach ($menuItems as $item) {
            $class = isset($item[3]) ? $item[3] : "";
            if ($currentUrl == $item[0]) {
                $class .= " active";
            } // Se a URL atual for igual ao link do item, adiciona a classe active
            echo "<li class='nav-item'>
                    <a class='nav-link $class' href='$item[0]'>
                        <img src='$item[1]' alt='$item[2]' class='icon-img icon-size'> $item[2]
                    </a>
                </li>";
        }
        ?>
    </ul>
</div>