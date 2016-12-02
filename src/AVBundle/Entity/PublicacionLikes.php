<?php

namespace AVBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PublicacionLikes
 *
 * @ORM\Table(name="Publicacion_likes", indexes={@ORM\Index(name="id_usuario", columns={"id_usuario"}), @ORM\Index(name="id_publicacion", columns={"id_publicacion"})})
 * @ORM\Entity
 */
class PublicacionLikes
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

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
     * @var \Publicacion
     *
     * @ORM\ManyToOne(targetEntity="Publicacion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_publicacion", referencedColumnName="id")
     * })
     */
    private $idPublicacion;



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
     * Set idUsuario
     *
     * @param \AVBundle\Entity\Usuario $idUsuario
     * @return PublicacionLikes
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
     * Set idPublicacion
     *
     * @param \AVBundle\Entity\Publicacion $idPublicacion
     * @return PublicacionLikes
     */
    public function setIdPublicacion(\AVBundle\Entity\Publicacion $idPublicacion = null)
    {
        $this->idPublicacion = $idPublicacion;

        return $this;
    }

    /**
     * Get idPublicacion
     *
     * @return \AVBundle\Entity\Publicacion 
     */
    public function getIdPublicacion()
    {
        return $this->idPublicacion;
    }
}
