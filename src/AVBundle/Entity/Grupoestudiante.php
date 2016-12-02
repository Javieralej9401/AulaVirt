<?php

namespace AVBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Grupoestudiante
 *
 * @ORM\Table(name="GrupoEstudiante", indexes={@ORM\Index(name="id_grupo", columns={"id_grupo"}), @ORM\Index(name="id_estudiante", columns={"id_estudiante"})})
 * @ORM\Entity
 */
class Grupoestudiante
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
     * @var \Grupo
     *
     * @ORM\ManyToOne(targetEntity="Grupo", inversedBy="Estudiantes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_grupo", referencedColumnName="id")
     * })
     */
    private $idGrupo;

    /**
     * @var \Usuario
     *
     * @ORM\ManyToOne(targetEntity="Usuario", inversedBy="GruposInscrito")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_estudiante", referencedColumnName="id")
     * })
     */
    private $idEstudiante;


    public function __toString(){

        return $this->idEstudiante->getNombre(). " "
        .$this->idEstudiante->getApellido();
    }
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
     * Set idGrupo
     *
     * @param \AVBundle\Entity\Grupo $idGrupo
     * @return Grupoestudiante
     */
    public function setIdGrupo(\AVBundle\Entity\Grupo $idGrupo = null)
    {
        $this->idGrupo = $idGrupo;

        return $this;
    }

    /**
     * Get idGrupo
     *
     * @return \AVBundle\Entity\Grupo 
     */
    public function getIdGrupo()
    {
        return $this->idGrupo;
    }

    /**
     * Set idEstudiante
     *
     * @param \AVBundle\Entity\Usuario $idEstudiante
     * @return Grupoestudiante
     */
    public function setIdEstudiante(\AVBundle\Entity\Usuario $idEstudiante = null)
    {
        $this->idEstudiante = $idEstudiante;

        return $this;
    }

    /**
     * Get idEstudiante
     *
     * @return \AVBundle\Entity\Usuario 
     */
    public function getIdEstudiante()
    {
        return $this->idEstudiante;
    }
}
