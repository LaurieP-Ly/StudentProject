<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClientsRepository")
 * @UniqueEntity( fields = {"username"}, message= "L'email doit être unique" )
 * @ORM\HasLifecycleCallbacks
 * @ApiResource
 */
class Clients implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $codePostale;

    /**
     * @ORM\Column(type="string", length=80)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=80)
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=80)
     * @Assert\Email(
     *     message = "Veuillez rentrer une email valide"
     * )
     *
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="8", minMessage="Votre mot de passe doit contenir au moins 8 caractères")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=4)
     *@Assert\Length(max="4")
     */
    private $civilite;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pays;

    /**
     * @ORM\Column(type="string", length=80)
     */
    private $telephone;

    /**
     * @ORM\Column(type="array")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     */
    private $pathAvatar; // Variable pour gérer l'enregistrement d'image dans le dossier img


    /**
     * @Assert\File(maxSize="6000000")
     */
    private $avatar;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $surnom;

    /**
     * @ORM\Column(type="datetime")
     */
    private $birthdate;


    private $temp;


    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setAvatar(UploadedFile $file = null)
    {
        $this->avatar = $file;
        // check if we have an old image path
        if (isset($this->pathAvatar)) {
            // store the old name to delete after the update
            $this->temp = $this->pathAvatar;
            $this->pathAvatar = null;
        } else {
            $this->pathAvatar = 'initial';
        }
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getCodePostale(): ?string
    {
        return $this->codePostale;
    }

    public function setCodePostale(string $codePostale): self
    {
        $this->codePostale = $codePostale;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $user): self
    {
        $this->username = $user;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getCivilite(): ?string
    {
        return $this->civilite;
    }

    public function setCivilite(string $civilite): self
    {
        $this->civilite = $civilite;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(string $pays): self
    {
        $this->pays = $pays;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    ////////////Methodes essentielles à l'utilisation de l'interface "User" et donc à l'utilisation de certaines fonctionnalité de Security/////////////////////////

    public function eraseCredentials(){

    }

    public function getSalt(){

    }

    //////////Initialisation des roles à "USER" pour tout nouveau client créé/////////////////////////
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPathAvatar(): ?string
    {
        return $this->pathAvatar;
    }

    public function setPathAvatar(?string $avatar): self
    {
        $this->pathAvatar = $avatar;

        return $this;
    }

    ///////////Eviter les problème d'enregistrement de l'image lors d'erreur de validation du formulaire///////////////////////////////
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getAvatar()) {
            // do whatever you want to generate a unique name
            $filename = $this->getSurnom().".jpg";
            $this->pathAvatar = $filename;
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        // the file property can be empty if the field is not required
        if (null === $this->getAvatar()) {
            return;
        }




        $this->getAvatar()->move(
            '../public/img',
            $this->getSurnom().".jpg"
        );

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink('../public/img/'.$this->getSurnom());
            // clear the temp image path
            $this->temp = null;
        }


        // clean up the file property as you won't need it anymore
        $this->avatar = null;








    }


    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        $file = '../public/img/'.$this->pathAvatar;
        if ($file) {
            unlink($file);
        }
    }


    ///////////////////////////////////////////////////////////////////////////////////////

    public function getSurnom(): ?string
    {
        return $this->surnom;
    }

    public function setSurnom(string $surnom): self
    {
        $this->surnom = $surnom;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }




}
