<?php

namespace AVBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Publicacion
 *
 * @ORM\Table(name="Publicacion", indexes={@ORM\Index(name="id_usuario", columns={"id_usuario"})})
 * @ORM\Entity
 */
class Publicacion
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    // *
    //  * @var \Grupo
    //  *
    //  * @ORM\ManyToOne(targetEntity="Tipousuario")
    //  * @ORM\JoinColumns({
    //  *   @ORM\JoinColumn(name="id_grupo", referencedColumnName="id")
    //  * })
     
    // private $id_grupo;
    /**
     * @var string
     *
     * @ORM\Column(name="titulo", type="string", length=255, nullable=false)
     */
    private $titulo;

    /**
     * @var string
     *
     * @ORM\Column(name="contenido", type="text", nullable=false)
     */
    private $contenido;

    /**
     * @var integer
     *
     * @ORM\Column(name="likes", type="integer", nullable=false)
     */
    private $likes;

    /**
     * @var string
     *
     * @ORM\Column(name="calidad", type="string", length=255, nullable=false)
     */
    private $calidad;

    /**
     * @var string
     *
     * @ORM\Column(name="recursos", type="text", nullable=false)
     */
    private $recursos;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime", nullable=false)
     */
    private $fecha = '0000-00-00 00:00:00';

    /**
     * @var \Usuario
     *
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_usuario", referencedColumnName="id")
     * })
     */
    private $idUsuario;



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set titulo
     *
     * @param string $titulo
     * @return Publicacion
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get titulo
     *
     * @return string 
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Set contenido
     *
     * @param string $contenido
     * @return Publicacion
     */
    public function setContenido($contenido)
    {
        $this->contenido = $contenido;

        return $this;
    }

    /**
     * Get contenido
     *
     * @return string 
     */
    public function getContenido()
    {
        return $this->contenido;
    }

    /**
     * Set likes
     *
     * @param integer $likes
     * @return Publicacion
     */
    public function setLikes($likes)
    {
        $this->likes = $likes;

        return $this;
    }

    /**
     * Get likes
     *
     * @return integer 
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * Set calidad
     *
     * @param string $calidad
     * @return Publicacion
     */
    public function setCalidad($calidad)
    {
        $this->calidad = $calidad;

        return $this;
    }

    /**
     * Get calidad
     *
     * @return string 
     */
    public function getCalidad()
    {
        return $this->calidad;
    }

    /**
     * Set recursos
     *
     * @param string $recursos
     * @return Publicacion
     */
    public function setRecursos($recursos)
    {
        $this->recursos = $recursos;

        return $this;
    }

    /**
     * Get recursos
     *
     * @return string 
     */
    public function getRecursos()
    {
        return $this->recursos;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Publicacion
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set idUsuario
     *
     * @param \AVBundle\Entity\Usuario $idUsuario
     * @return Publicacion
     */
    public function setIdUsuario(\AVBundle\Entity\Usuario $idUsuario = null)
    {
        $this->idUsuario = $idUsuario;

        return $this;
    }

    /**
     * Get idUsuario
     *
     * @return \AVBundle\Entity\Usuario 
     */
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    /**
     * Set id_grupo
     *
     * @param \AVBundle\Entity\Tipousuario $idGrupo
     * @return Publicacion
     */
    public function setIdGrupo(\AVBundle\Entity\Tipousuario $idGrupo = null)
    {
        $this->id_grupo = $idGrupo;

        return $this;
    }

    /**
     * Get id_grupo
     *
     * @return \AVBundle\Entity\Tipousuario 
     */
    public function getIdGrupo()
    {
        return $this->id_grupo;
    }
}
