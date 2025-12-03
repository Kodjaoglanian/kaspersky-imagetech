<?php
/**
 * Template de fallback exigido pelo WordPress.
 * Mantém um loop básico para garantir compatibilidade e permitir instalação do tema.
 */

get_header();
?>

<main class="wp-default-wrapper">
  <?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>
      <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <header class="entry-header">
          <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
        </header>
        <div class="entry-content">
          <?php the_content(); ?>
        </div>
      </article>
    <?php endwhile; ?>
  <?php else : ?>
    <section class="no-results">
      <h1><?php esc_html_e('Nada encontrado', 'kaspersky-imagetech'); ?></h1>
      <p><?php esc_html_e('Crie uma página e defina-a como Página Inicial para carregar o layout completo da landing.', 'kaspersky-imagetech'); ?></p>
    </section>
  <?php endif; ?>
</main>

<?php get_footer(); ?>
