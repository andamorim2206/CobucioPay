<?php

namespace App\Service;

use App\Repositories\TransactionRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TransactionService
{
    protected TransactionRepositoryInterface $repository;

    private UserService $userService;

    private WalletService $walletService;

    public function __construct(TransactionRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function transfer(string $token, Request $request)
    {
        $senderUserId = $this->getUser()->findUserByToken($token);

        $receiverUserId = $this->getUser()->findUserByEmail($request->input('email'));

        if ($senderUserId->getId() === $receiverUserId->getId()) {
            throw ValidationException::withMessages([
                'email' => 'Você não pode transferir dinheiro para si mesmo.',
            ]);
        }

        $senderWallet = $this->getWallet()->loadWallets($senderUserId->getId());
        $receiverWallet = $this->getWallet()->loadWallets($receiverUserId->getId());

        if (!$senderWallet) {
            throw ValidationException::withMessages(['sender' => 'Carteira do remetente não encontrada.']);
        }
        if (!$receiverWallet) {
            throw ValidationException::withMessages(['receiver' => 'Carteira do destinatário não encontrada.']);
        }

    }

    public function setUser(UserService $userService): self
    {
        $this->userService = $userService;

        return $this;
    }

    public function getUser(): UserService
    {
        return $this->userService;
    }

    public function setWallet(WalletService $walletService): self
    {
        $this->walletService = $walletService;

        return $this;
    }

    public function getWallet(): WalletService
    {
        return $this->walletService;
    }
}