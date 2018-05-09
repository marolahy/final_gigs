<?php
namespace App\Datatables;
use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Style;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;
use Sg\DatatablesBundle\Datatable\Column\ImageColumn;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
/**
 * Class OrderDatatable
 */
class GigsDatatable extends AbstractDatatable
{
    /**
     * Get data.
     *
     * @return null|string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function buildDatatable(array $options = array())
    {
        $url = $this->router->generate('index',[]);
        $this->language->set(array(
            'cdn_language_by_locale' => true,
        ));
        $this->ajax->set(array());
        $this->options->set(array(
            'classes' => Style::BOOTSTRAP_3_STYLE,
            'individual_filtering' => true,
            'individual_filtering_position' => 'head',
            'order_cells_top' => true,
        ));
        $this->features->set(array());
        $this->columnBuilder
            ->add('name', Column::class, array(
                'title' => 'Name',
                'class_name' => 'bezama'
            ))
            ->add('price', Column::class, array(
                'title' => 'Price',
            ))
            ->add('background_image', Column::class, array(
                'title' => 'Background',
                'dql' => "CONCAT('<div class=\"thumbnail\"><img src=\"".$url."images/gigs/',gigs.id,'/',gigs.background_image,'\" /></div>')"
            ))
            ->add('featured', Column::class, array(
                'title' => 'Featured',
            ))
            ->add(null, ActionColumn::class, array(
                'title' => 'Actions',
                'start_html' => '<div class="start_actions">',
                'end_html' => '</div>',
                'actions' => array(
                  array(
                    'route' => 'gigs_show',
                    'route_parameters' => array(
                        'id' => 'id',
                    ),
                  'icon' => 'glyphicon glyphicon-eye-open',
                  'label' => 'View',
                  'confirm' => true,
                  'confirm_message' => 'Are you sure?',
                  'attributes' => array(
                      'rel' => 'tooltip',
                      'title' => 'Show',
                      'class' => 'btn btn-primary btn-xs',
                      'role' => 'button',
                  ),
              ),
              array(
                'route' => 'gigs_edit',
                'route_parameters' => array(
                    'id' => 'id',
                ),
              'icon' => 'glyphicon glyphicon-edit',
              'label' => 'Edit',
              'confirm' => true,
              'confirm_message' => 'Are you sure?',
              'attributes' => array(
                  'rel' => 'tooltip',
                  'title' => 'Show',
                  'class' => 'btn btn-primary btn-xs',
                  'role' => 'button',
              ),
          ),
          array(
            'route' => 'gigs_delete',
            'route_parameters' => array(
                'id' => 'id',
            ),
          'icon' => 'glyphicon glyphicon-trash',
          'label' => 'Delete',
          'confirm' => true,
          'confirm_message' => 'Are you sure?',
          'attributes' => array(
              'rel' => 'tooltip',
              'title' => 'Show',
              'class' => 'btn btn-primary btn-xs',
              'role' => 'button',
          ),
      ),
            )
          )
          );
    }
    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'App\Entity\Gigs';
    }
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'gigs_datatable';
    }
}
