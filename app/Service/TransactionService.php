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

    private string $type;
    private float $amount = 0;
    private string $sender;
    private string $receiver;
    private string $id;

    private string $status;


    public function __construct(TransactionRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function handlerTransfer(string $token, Request $request)
    {
        $senderUser = $this->getUser()->findUserByToken($token);
        $receiverUser = $this->getUser()->findUserByEmail($request->input('email'));

        if ($senderUser->getId() === $receiverUser->getId()) {
            throw ValidationException::withMessages([
                'email' => 'Você não pode transferir dinheiro para si mesmo.',
            ]);
        }

        $amount = (float) $request->input('amount', 0);
        if ($amount <= 0) {
            throw ValidationException::withMessages(['amount' => 'Valor inválido.']);
        }

        $senderWallet = $this->getWallet()->loadWallets($senderUser->getId());
        $receiverWallet = $this->getWallet()->loadWallets($receiverUser->getId());

        if (!$senderWallet) {
            throw ValidationException::withMessages(['sender' => 'Carteira do remetente não encontrada.']);
        }
        if (!$receiverWallet) {
            throw ValidationException::withMessages(['receiver' => 'Carteira do destinatário não encontrada.']);
        }

        \DB::transaction(function () use ($senderWallet, $receiverWallet, $senderUser, $receiverUser, $amount) {
            if ($senderWallet->getBalance() < $amount) {
                throw ValidationException::withMessages([
                    'balance' => 'Saldo insuficiente para realizar a transferência.',
                ]);
            }

            $valuePaid = $senderWallet->getBalance() - $amount;
            $senderWallet->setBalance($valuePaid);

            $valueAdd = $receiverWallet->getBalance() + $amount;
            $receiverWallet->setBalance($valueAdd);

            $this->transfer($senderWallet, $receiverWallet, $amount);
            $senderWallet->updateWallet($senderWallet, $amount);
            $this->deposit($senderWallet, $receiverWallet, $amount);
            $receiverWallet->updateWallet($receiverWallet, $amount);
        });
    }

    public function transfer(WalletService $senderWallet, WalletService $receiverWallet, float $amount): void
    {
        $this
            ->setSender($senderWallet->getId())
            ->setReceiver($receiverWallet->getId())
            ->setAmount($amount)
            ->setType('transfer');

        $this->repository->insertTransaction($this);
    }

    public function deposit(WalletService $senderWallet, WalletService $receiverWallet, float $amount): void
    {
        $this
            ->setSender($senderWallet->getId())
            ->setReceiver($receiverWallet->getId())
            ->setAmount($amount)
            ->setType('deposit');

        $this->repository->insertTransaction($this);
    }

    public function loadExtract(string $token): array
    {
        $user = $this->getUser()->findUserByToken($token);
        $wallet = $this->getWallet()->loadWallets($user->getId());

        return $this->repository->loadExtract($wallet->getId());
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

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setSender(string $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function getSender(): string
    {
        return $this->sender;
    }

    public function setReceiver(string $receiver): self
    {
        $this->receiver = $receiver;

        return $this;
    }

    public function getReceiver(): string
    {
        return $this->receiver;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): string
    {
        return $this->id;
    }
}