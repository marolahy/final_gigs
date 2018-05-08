<?php
namespace App\Datatables;
use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Style;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
/**
 * Class OrderDatatable
 */
class CategoryDatatable extends AbstractDatatable
{

    /**
     * {@inheritdoc}
     */
    public function buildDatatable(array $options = array())
    {
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
            ->add('countGigs', Column::class, array(
                'title' => 'Gigs',
                'dql' => '(SELECT COUNT({p}) FROM App:Gigs {p} WHERE {p}.category =category.id)',
                'searchable'=>false
            ))
            ->add(null, ActionColumn::class, array(
                'title' => 'Actions',
                'start_html' => '<div class="start_actions">',
                'end_html' => '</div>',
                'actions' => array(
                  array(
                    'route' => 'category_show',
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
                'route' => 'category_edit',
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
            'route' => 'category_delete',
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
              )
            )
          )
          );
    }
    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'App\Entity\Category';
    }
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'category_datatable';
    }
}
