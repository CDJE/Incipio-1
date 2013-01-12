<?php

namespace mgate\SuiviBundle\Manager;

use Doctrine\ORM\EntityManager;
use mgate\SuiviBundle\Manager\BaseManager;
use mgate\SuiviBundle\Entity\Etude as Etude;

class EtudeManager extends \Twig_Extension
{
    protected $em;
    protected $tva;

    public function __construct(EntityManager $em, $tva)
    {
        $this->em = $em;
        $this->tva = $tva;
    }
    
    // Pour utiliser les fonctions depuis twig
    public function getName()
    {
        return 'mgate_EtudeManager';
    }
    // Pour utiliser les fonctions depuis twig
    public function getFunctions()
    {
        return array(
            'getRefEtude' => new \Twig_Function_Method($this, 'getRefEtude')
        );
    }

    /**
     * Get montant total HT
     */
    public function getTotalJEHHT(Etude $etude)
    {
        $total=0;
        foreach ($etude->getPhases() as $phase) {
            $total += $phase->getNbrJEH()*$phase->getPrixJEH();
        }
        
        return $total;
    }
    /**
     * Get montant total HT
     */
    public function getTotalHT(Etude $etude)
    {
        $total = $etude->getFraisDossier()+$this->getTotalJEHHT($etude);
        
        return $total;
    }
        
    /**
     * Get montant total TTC
     */
    public function getTotalTTC(Etude $etude)
    {      
        return $this->getTotalHT($etude)*(1+$this->tva);
    }
    
    /**
     * Get nombre de JEH
     */
    public function getNbrJEH(Etude $etude)
    {
        $total = 0;
        
        foreach ($etude->getPhases() as $phase) {
            $total += $phase->getPrixJEH();
        }
        
        return $total;
    }  
    
    /**
     * Get référence de l'etude
     */
    public function getRefEtude(Etude $etude)
    {      
        return "[M-GaTE]".(string)($etude->getMandat()*100+$etude->getNum());
    }
    
    /**
     * Get référence document
     */
    public function getRefDoc(Etude $etude, $doc, $version)
    {      
        return $this->getRefEtude($etude)."-".$doc."-".$version; //TODO faire les autres type de docs, genre RM
    }
    
    /**
     * Get nouveau numéro d'etude, pour valeur par defaut dans formulaire
     */
    public function getNouveauNumero($mandat=5)
    {      
        $qb = $this->em->createQueryBuilder();
        
        $query = $qb->select('e.num')
                   ->from('mgateSuiviBundle:Etude', 'e')
                   ->andWhere('e.mandat = :mandat')
                       ->setParameter('mandat', $mandat)
                   ->orderBy('e.num', 'DESC');
                    
        $value=$query->getQuery()->setMaxResults(1)->getOneOrNullResult();
        if($value)
            return $value['num']+1;
        else
            return 1;
    }
    
    public function getNomClient(Etude $etude)
    {
        return $etude->getAp()->getSignataire2()->getNom()." ".$etude->getAp()->getSignataire2()->getPrenom();
    }
    
    public function getDescriptionPrestation(Etude $etude)
    {
        return $etude->getDescriptionPrestation();
    }
    
    public function getTypePrestation(Etude $etude)
    {
        return $etude->getTypePrestation();
    }
    
    public function getPresentationProjet(Etude $etude)
    {
        return $etude->getPresentationProjet();
    }
    
    public function getFonctionSignataire(Etude $etude)
    {
        return $etude->getAp()->getSignataire2()->getPoste();
    }
    
    public function getCapaciteDev(Etude $etude)
    {
        return $etude->getCompetences();
    }
    
    public function getNomSuiveur(Etude $etude)
    {
        return $etude->getSuiveur()->getNom()." ".$etude->getSuiveur()->getPrenomNom();
    }
    
    public function getMailSuiveur(Etude $etude)
    {
        return $etude->getSuiveur()->getEmail();
    }
    
    public function getTelSuiveur(Etude $etude)
    {
        return $etude->getSuiveur()->getMobile();
    }
    
    public function getEntiteSociale(Etude $etude)
    {
        return $etude->getProspect()->getEntite();
    }
    
    public function getMoisLancement(Etude $etude)
    {
        $phases = $etude->getPhases();
        
        return $phases['0']->getDateDebut()->format('F');
    }
    
    public function getMoisFin(Etude $etude)
    {
        $phases = $etude->getPhases();
        $nbphases = count($phases);
        //ajouter les délais au début de la phases puis prendre le mois
        $delai = $phases[$nbphases-1]->getDelai();
        $DateDebutPhase = $phases[$nbphases-1]->getDateDebut();
        $DateFin = $DateDebutPhase->modify('+'.$delai.' day');
        return $DateFin->format('F');
    }
    
    

    public function getRepository()
    {
        return $this->em->getRepository('mgateSuiviBundle:Etude');
    }

}