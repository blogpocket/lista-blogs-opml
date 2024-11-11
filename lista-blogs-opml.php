<?php
/*
Plugin Name: Lista de Blogs OPML Simplificado
Description: Lee un archivo OPML y crea un bloque o shortcode para mostrar la lista de blogs con enlaces RSS.
Version: 1.2
Author: Tu Nombre
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Salir si se accede directamente
}

// Definir constantes
define( 'LBOPMLS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'LBOPMLS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Incluir acciones y filtros necesarios
add_action( 'init', 'lbopmls_registrar_bloque' );
add_action( 'enqueue_block_assets', 'lbopmls_cargar_estilos' );

function lbopmls_registrar_bloque() {
    // Registrar el bloque
    register_block_type( 'lbopmls/lista-blogs', [
        'render_callback' => 'lbopmls_renderizar_bloque',
        'attributes'      => [
            'fontSize' => [
                'type'    => 'number',
                'default' => 16,
            ],
        ],
    ] );
}

function lbopmls_renderizar_bloque( $attributes ) {
    $font_size = isset( $attributes['fontSize'] ) ? intval( $attributes['fontSize'] ) : 16;
    $opml_url  = site_url( '/archivo.opml' ); // URL del archivo OPML en la raíz
    $opml_file = ABSPATH . 'archivo.opml';    // Ruta absoluta al archivo OPML

    if ( ! file_exists( $opml_file ) ) {
        return '<p>No se encontró el archivo OPML en la raíz de la instalación de WordPress.</p>';
    }

    $opml_data = simplexml_load_file( $opml_file );
    if ( ! $opml_data ) {
        return '<p>Error al procesar el archivo OPML.</p>';
    }

    $secciones = lbopmls_obtener_secciones( $opml_data );

    ob_start();
    ?>
    <div style="font-size: <?php echo esc_attr( $font_size ); ?>px;" class="lbopmls-lista-blogs">
        <h1><a href="<?php echo esc_url( $opml_url ); ?>" target="_blank" rel="noopener noreferrer">OPML</a></h1>
        <?php foreach ( $secciones as $seccion ) : ?>
            <div>
                <h2><?php echo esc_html( $seccion['titulo'] ); ?></h2>
                <ul>
                    <?php foreach ( $seccion['blogs'] as $blog ) : ?>
                        <li>
                            <a href="<?php echo esc_url( $blog['url'] ); ?>" target="_blank" rel="noopener noreferrer">
                                <?php echo esc_html( $blog['titulo'] ); ?>
                            </a>
                            <?php if ( ! empty( $blog['rss'] ) ) : ?>
                                ( <a href="<?php echo esc_url( $blog['rss'] ); ?>" target="_blank" rel="noopener noreferrer">RSS</a> )
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
}

function lbopmls_obtener_secciones( $opml_data ) {
    $secciones = [];
    foreach ( $opml_data->body->outline as $seccion ) {
        $titulo_seccion = (string) $seccion['text'];
        $blogs = [];
        foreach ( $seccion->outline as $blog ) {
            if ( isset( $blog['htmlUrl'] ) ) {
                $blogs[] = [
                    'titulo' => (string) $blog['text'],
                    'url'    => (string) $blog['htmlUrl'],
                    'rss'    => isset( $blog['xmlUrl'] ) ? (string) $blog['xmlUrl'] : ''
                ];
            }
        }
        $secciones[] = [
            'titulo' => $titulo_seccion,
            'blogs'  => $blogs
        ];
    }
    return $secciones;
}

function lbopmls_cargar_estilos() {
    // Cargar estilos en el editor y en el front-end
    wp_enqueue_style(
        'lbopmls-estilos',
        LBOPMLS_PLUGIN_URL . 'estilos.css',
        [],
        filemtime( LBOPMLS_PLUGIN_DIR . 'estilos.css' )
    );
}

// Agregar el shortcode para utilizar el plugin sin necesidad de usar bloques
add_shortcode( 'lista_blogs_opml', 'lbopmls_renderizar_shortcode' );

function lbopmls_renderizar_shortcode( $atts ) {
    $atts = shortcode_atts( [
        'font_size' => 16,
    ], $atts, 'lista_blogs_opml' );

    $font_size = intval( $atts['font_size'] );
    $opml_url  = site_url( '/archivo.opml' ); // URL del archivo OPML en la raíz
    $opml_file = ABSPATH . 'archivo.opml';    // Ruta absoluta al archivo OPML

    if ( ! file_exists( $opml_file ) ) {
        return '<p>No se encontró el archivo OPML en la raíz de la instalación de WordPress.</p>';
    }

    $opml_data = simplexml_load_file( $opml_file );
    if ( ! $opml_data ) {
        return '<p>Error al procesar el archivo OPML.</p>';
    }

    $secciones = lbopmls_obtener_secciones( $opml_data );

    ob_start();
    ?>
    <div style="font-size: <?php echo esc_attr( $font_size ); ?>px;" class="lbopmls-lista-blogs">
        <h2><a href="<?php echo esc_url( $opml_url ); ?>" target="_blank" rel="noopener noreferrer">OPML</a></h2>
        <?php foreach ( $secciones as $seccion ) : ?>
            <div>
                <h2><?php echo esc_html( $seccion['titulo'] ); ?></h2>
                <ul>
                    <?php foreach ( $seccion['blogs'] as $blog ) : ?>
                        <li>
                            <a href="<?php echo esc_url( $blog['url'] ); ?>" target="_blank" rel="noopener noreferrer">
                                <?php echo esc_html( $blog['titulo'] ); ?>
                            </a>
                            <?php if ( ! empty( $blog['rss'] ) ) : ?>
                                ( <a href="<?php echo esc_url( $blog['rss'] ); ?>" target="_blank" rel="noopener noreferrer">RSS</a> )
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
}
