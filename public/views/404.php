<?php 
if (!defined('BASE_URL')) {
    define('BASE_URL', '/Zava/');
}
include_once 'c:/xampp/htdocs/Zava/public/componentes/php/header.php'; 
?>
<style>
    .container-404 {
        text-align: center;
        margin: 100px auto;
        max-width: 600px;
    }
    .container-404 h1 {
        font-size: 5rem;
        color: #333;
    }
    .container-404 p {
        font-size: 1.2rem;
        color: #666;
    }
    .container-404 a {
        display: inline-block;
        margin-top: 20px;
        padding: 10px 20px;
        background-color: #007bff;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
    }
</style>
<div class="container-404">
    <h1>404</h1>
    <p>Página no encontrada</p>
    <p>La página que buscas no existe o ha sido movida.</p>
    <a href="<?php echo BASE_URL; ?>">Volver al inicio</a>
</div>
<?php include_once 'c:/xampp/htdocs/Zava/public/componentes/php/footer.php'; ?>
