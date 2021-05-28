<?php

namespace App\Service\Cart;

use App\Repository\TrackRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    protected $session;
    protected $trackRepository;

    public function __construct(SessionInterface $session, TrackRepository $trackRepository)
    {
        $this->session = $session;
        $this->trackRepository = $trackRepository;
    }

    public function add(int $id)
    {
        $panier = $this->session->get('panier', []);

        if (!empty($panier[$id])) {
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }

        $this->session->set('panier', $panier);
    }

    public function remove(int $id)
    {
        $panier = $this->session->get('panier', []);

        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }

        $this->session->set('panier', $panier);
    }

    public function getFullCart(): array
    {
        $panier = $this->session->get('panier', []);

        $panierWithData = [];

        foreach ($panier as $id => $quantity) {
            $panierWithData[] = [
                'track' => $this->trackRepository->find($id),
                'quantity' => $quantity
            ];
        }

        return $panierWithData;
    }

    public function getTotal(): float
    {
        $total = 0;

        foreach ($this->getFullCart() as $item) {
            $total += $item['track']->getPrice() * $item['quantity'];
        }

        return $total;
    }
}
