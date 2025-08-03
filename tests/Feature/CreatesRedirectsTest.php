<?php

use Rias\StatamicRedirect\Enums\MatchTypeEnum;
use Rias\StatamicRedirect\Facades\Redirect;
use Statamic\Facades\Collection;
use Statamic\Facades\Entry;
use Statamic\Facades\User;
use Statamic\Structures\CollectionStructure;

it('creates redirects when the uri of an entry changes', function () {
    Collection::make('blog')->routes('/blog/{slug}')->save();

    Entry::make()
        ->id('the-entry')
        ->collection('blog')
        ->slug('the-existing-entry')
        ->data(['title' => 'The Existing Entry'])
        ->save();

    $entry = Entry::all()->first();

    expect(Redirect::all())->toHaveCount(0);

    $entry->slug('the-new-entry');
    $entry->save();

    expect(Redirect::all())->toHaveCount(1);
    tap(Redirect::all()->first(), function ($redirect) {
        expect($redirect->source())->toEqual('/blog/the-existing-entry')
            ->and($redirect->destination())->toEqual('/blog/the-new-entry')
            ->and($redirect->type())->toEqual('301')
            ->and($redirect->matchType())->toEqual(MatchTypeEnum::EXACT);
    });
});

it('does not create one if the entry isn\'t published', function () {
    Collection::make('blog')->routes('/blog/{slug}')->save();

    Entry::make()
        ->id('the-entry')
        ->collection('blog')
        ->slug('the-existing-entry')
        ->data(['title' => 'The Existing Entry'])
        ->published(false)
        ->save();

    $entry = Entry::all()->first();

    expect(Redirect::all())->toHaveCount(0);

    $entry->slug('the-new-entry');
    $entry->save();

    expect(Redirect::all())->toHaveCount(0);
});

it('does not create one if the entry uri stays the same', function () {
    Collection::make('blog')->routes('/blog/{slug}')->save();

    Entry::make()
        ->id('the-entry')
        ->collection('blog')
        ->slug('the-existing-entry')
        ->data(['title' => 'The Existing Entry'])
        ->save();

    $entry = Entry::all()->first();

    expect(Redirect::all())->toHaveCount(0);

    $entry->slug('the-existing-entry');
    $entry->save();

    expect(Redirect::all())->toHaveCount(0);
});

it('does not create one if configuration is disabled', function () {
    config()->set('statamic.redirect.create_entry_redirects', false);

    Collection::make('blog')->routes('/blog/{slug}')->save();

    Entry::make()
        ->id('the-entry')
        ->collection('blog')
        ->slug('the-existing-entry')
        ->data(['title' => 'The Existing Entry'])
        ->save();

    $entry = Entry::all()->first();

    expect(Redirect::all())->toHaveCount(0);

    $entry->slug('the-new-entry');
    $entry->save();

    expect(Redirect::all())->toHaveCount(0);
});

it('removes redirects with a source the same as the new uri', function () {
    Collection::make('blog')->routes('/blog/{slug}')->save();

    Entry::make()
        ->id('the-entry')
        ->collection('blog')
        ->slug('the-existing-entry')
        ->data(['title' => 'The Existing Entry'])
        ->save();

    $entry = Entry::all()->first();

    expect(Redirect::all())->toHaveCount(0);

    $entry->slug('the-new-entry');
    $entry->save();

    expect(Redirect::all())->toHaveCount(1);

    $entry->slug('the-existing-entry');
    $entry->save();

    expect(Redirect::all())->toHaveCount(1);
});

it('doesn\'t remove redirects with a source the same as the new uri when configured', function () {
    config()->set('statamic.redirect.delete_conflicting_redirects', false);

    Collection::make('blog')->routes('/blog/{slug}')->save();

    Entry::make()
        ->id('the-entry')
        ->collection('blog')
        ->slug('the-existing-entry')
        ->data(['title' => 'The Existing Entry'])
        ->save();

    $entry = Entry::all()->first();

    expect(Redirect::all())->toHaveCount(0);

    $entry->slug('the-new-entry');
    $entry->save();

    expect(Redirect::all())->toHaveCount(1);

    $entry->slug('the-existing-entry');
    $entry->save();

    expect(Redirect::all())->toHaveCount(2);
});


