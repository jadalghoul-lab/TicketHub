<?php

use App\Livewire\Public\SupportForm;
use App\Mail\SupportContactMail;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;

use function Pest\Laravel\get;

it('can view the support page', function () {
    get(route('public.support'))
        ->assertStatus(200)
        ->assertSee('How can we help?')
        ->assertSeeLivewire(SupportForm::class);
});

it('can submit the support form and queue the email via livewire', function () {
    Mail::fake();

    Livewire::test(SupportForm::class)
        ->set('name', 'John Doe')
        ->set('email', 'john@example.com')
        ->set('subject', 'Ticket Issue')
        ->set('message', 'I have an issue with my ticket.')
        ->call('submit')
        ->assertHasNoErrors()
        ->assertSet('success', true);

    Mail::assertQueued(SupportContactMail::class, function (SupportContactMail $mail) {
        return $mail->data['email'] === 'john@example.com' &&
               $mail->data['subject'] === 'Ticket Issue' &&
               $mail->hasTo('support@tickethub.com');
    });
});

it('validates the support form via livewire', function () {
    Livewire::test(SupportForm::class)
        ->call('submit')
        ->assertHasErrors(['name', 'email', 'subject', 'message']);
});
