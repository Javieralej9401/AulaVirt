<?php

namespace AVBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Grupo
 *
 * @ORM\Table(name="Grupo", indexes={@ORM\Index(name="idProfesor", columns={"idProfesor"}), @ORM\Index(name="materia", columns={"materia"})})
 * @ORM\Entity
 */
class Grupo
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
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=100, nullable=false, unique=true)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="recursos", type="text", nullable=true)
     */
    private $recursos;

    /**
     * @var \Usuario
     *
     * @ORM\ManyToOne(targetEntity="Usuario", inversedBy="grupos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idProfesor", referencedColumnName="id")
     * })
     */
    private $idprofesor;

    /**
     * @var \Materia
     *
     * @ORM\ManyToOne(targetEntity="Materia")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="materia", referencedColumnName="id")
     * })
     */
    private $materia;

     /**
     * @var \Grupoestudiante
     *
     * @ORM\OneToMany(targetEntity="Grupoestudiante", cascade={"persist","remove"}, mappedBy="idGrupo")
     */
    private $Estudiantes;


    public function __toString(){
        
        return $this->nombre;
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
     * Set nombre
     *
     * @param string $nombre
     * @return Grupo
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set recursos
     *
     * @param string $recursos
     * @return Grupo
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
     * Set idprofesor
     *
     * @param \AVBundle\Entity\Usuario $idprofesor
     * @return Grupo
     */
    public function setIdprofesor(\AVBundle\Entity\Usuario $idprofesor = null)
    {
        $this->idprofesor = $idprofesor;

        return $this;
    }

    /**
     * Get idprofesor
     *
     * @return \AVBundle\Entity\Usuario 
     */
    public function getIdprofesor()
    {
        return $this->idprofesor;
    }

    /**
     * Set materia
     *
     * @param \AVBundle\Entity\Materia $materia
     * @return Grupo
     */
    public function setMateria(\AVBundle\Entity\Materia $materia = null)
    {
        $this->materia = $materia;

        return $this;
    }

    /**
     * Get materia
     *
     * @return \AVBundle\Entity\Materia 
     */
    public function getMateria()
    {
        return $this->materia;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->Estudiantes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add Estudiantes
     *
     * @param \AVBundle\Entity\Grupoestudiante $estudiantes
     * @return Grupo
     */
    public function addEstudiante(\AVBundle\Entity\Grupoestudiante $estudiantes)
    {
        $this->Estudiantes[] = $estudiantes;

        return $this;
    }

    /**
     * Remove Estudiantes
     *
     * @param \AVBundle\Entity\Grupoestudiante $estudiantes
     */
    public function removeEstudiante(\AVBundle\Entity\Grupoestudiante $estudiantes)
    {
        $this->Estudiantes->removeElement($estudiantes);
    }

    /**
     * Get Estudiantes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEstudiantes()
    {
        return $this->Estudiantes;
    }
    public function setEstudiantes($Estudiantes)
    {
        $this->Estudiantes=$Estudiantes;

        foreach ($Estudiantes as $Estudiante) {
            $Estudiante->setIdGrupo($this);
        }
    }
}
