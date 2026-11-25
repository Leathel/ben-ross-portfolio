<?php

use App\Models\ContactMessage;
use App\Models\Experience;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Testing\AssertableInertia as Assert;

test('home page renders the profile and the XRecipes feature', function () {
    Profile::factory()->create([
        'name' => 'Ben Ross',
        'headline' => 'Senior Full Stack Developer',
    ]);
    Project::factory()->create([
        'title' => 'XRecipes',
        'slug' => 'xrecipes-independent-software-product',
        'summary' => 'A recipe-sharing platform I designed, built and run solo. Launched November 2026, still early stage.',
        'is_featured' => true,
        'sort_order' => 1,
    ]);
    Project::factory()->create([
        'title' => 'Other Project',
        'is_featured' => true,
        'sort_order' => 2,
    ]);

    $this->get(route('home'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Portfolio/Home')
            ->where('profile.name', 'Ben Ross')
            ->has('featuredProject')
            ->where('featuredProject.slug', 'xrecipes-independent-software-product')
            ->where('featuredProject.summary', 'A recipe-sharing platform I designed, built and run solo. Launched November 2026, still early stage.')
            ->missing('featuredProjects'));
});

test('home page exposes a separate card image for the featured project', function () {
    Project::factory()->create([
        'title' => 'XRecipes',
        'is_featured' => true,
        'image_url' => '/storage/images/preview.png',
        'card_image_url' => '/storage/images/card.png',
    ]);

    $this->get(route('home'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('featuredProject.image_url', '/storage/images/preview.png')
            ->where('featuredProject.card_image_url', '/storage/images/card.png'));
});

test('home page falls back to the preview image when no card image is set', function () {
    Project::factory()->create([
        'title' => 'XRecipes',
        'is_featured' => true,
        'image_url' => '/storage/images/preview.png',
        'card_image_url' => null,
    ]);

    $this->get(route('home'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('featuredProject.image_url', '/storage/images/preview.png')
            ->where('featuredProject.card_image_url', null));
});

test('about page renders outside the code content and skills', function () {
    Profile::factory()->create([
        'name' => 'Ben Ross',
        'outside_the_code_html' => '<p>Photography and hiking.</p>',
    ]);
    Experience::factory()->create([
        'company' => 'Northstar Studio',
        'role' => 'Senior Developer',
        'sort_order' => 1,
    ]);
    Skill::factory()->create(['name' => 'Laravel', 'sort_order' => 1]);

    $this->get(route('about'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Portfolio/About')
            ->where('profile.outside_the_code_html', '<p>Photography and hiking.</p>')
            ->missing('experience')
            ->where('skills.0.name', 'Laravel'));
});

test('home page shows position summaries without experience details', function () {
    Profile::factory()->create();
    Experience::factory()->create([
        'company' => 'Northstar Studio',
        'role' => 'Senior Developer',
        'description' => '<p>Owned the full platform.</p>',
        'skill_tags' => ['PHP', 'JavaScript'],
        'sort_order' => 1,
    ]);

    $this->get(route('home'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('experience.0.company', 'Northstar Studio')
            ->where('experience.0.role', 'Senior Developer')
            ->missing('experience.0.description')
            ->missing('experience.0.skill_tags'));
});

test('projects route renders only the XRecipes case study', function () {
    Profile::factory()->create(['name' => 'Ben Ross']);
    $project = Project::factory()->create([
        'title' => 'XRecipes',
        'slug' => 'xrecipes-independent-software-product',
        'category' => 'Independent Project',
        'is_featured' => true,
        'sort_order' => 1,
    ]);
    Project::factory()->create([
        'title' => 'Other Project',
        'is_published' => true,
        'is_featured' => false,
        'sort_order' => 2,
    ]);

    $this->get(route('projects.show'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Portfolio/Projects/Show')
            ->where('project.slug', $project->slug)
            ->where('project.title', 'XRecipes')
            ->missing('relatedProjects'));
});

test('public case study follows the published project after its slug is edited', function () {
    Project::factory()->create([
        'title' => 'XRecipes',
        'slug' => 'xrecipes',
        'is_featured' => true,
    ]);

    $this->get(route('projects.show'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->where('project.slug', 'xrecipes'));

    $this->get(route('home'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->where('featuredProject.slug', 'xrecipes'));
});

test('public skills page is no longer available', function () {
    $this->get('/skills')->assertNotFound();
});

test('search engines are told not to index the site', function () {
    Profile::factory()->create();

    expect(file_get_contents(public_path('robots.txt')))
        ->toContain('User-agent: *')
        ->toContain('Disallow: /');

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('<meta name="robots" content="noindex, nofollow">', false);
});

test('contact page renders the profile contact details', function () {
    Profile::factory()->create([
        'name' => 'Ben Ross',
        'email' => 'ben@example.com',
    ]);

    $this->get(route('contact'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Portfolio/Contact')
            ->where('profile.email', 'ben@example.com'));
});

test('contact form stores a valid message', function () {
    $this->post(route('contact.store'), [
        'name' => 'Ada Lovelace',
        'email' => 'ada@example.com',
        'subject' => 'Let us work together',
        'message' => 'I would love to discuss a project with you.',
    ])
        ->assertRedirect(route('contact'))
        ->assertSessionHas('status', 'Your message has been sent.');

    $this->assertDatabaseHas('contact_messages', [
        'email' => 'ada@example.com',
        'subject' => 'Let us work together',
    ]);
});

test('contact form rejects an empty submission', function () {
    $this->from(route('contact'))
        ->post(route('contact.store'), [])
        ->assertRedirect(route('contact'))
        ->assertSessionHasErrors(['name', 'email', 'message']);

    $this->assertDatabaseCount('contact_messages', 0);
});

test('guest users are redirected from every admin page', function (string $routeName) {
    $this->get(route($routeName))
        ->assertRedirect(route('login'));
})->with([
    'dashboard' => ['admin.dashboard'],
    'projects' => ['admin.projects.index'],
    'skills' => ['admin.skills.index'],
    'experience' => ['admin.experience.index'],
    'profile' => ['admin.profile.edit'],
    'messages' => ['admin.messages.index'],
]);

test('authenticated users can view the admin overview', function () {
    Profile::factory()->create(['name' => 'Ben Ross']);
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Dashboard')
            ->where('profile.name', 'Ben Ross')
            ->has('stats'));
});

test('admin can create a project with a generated slug and technologies', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('admin.projects.store'), [
            'title' => 'My New Project',
            'summary' => 'A short summary of the project.',
            'description' => 'A longer description.',
            'technologies' => 'Laravel, Vue',
            'is_featured' => true,
            'sort_order' => 2,
        ])
        ->assertRedirect(route('admin.projects.index'));

    $this->assertDatabaseHas('projects', [
        'slug' => 'my-new-project',
        'is_featured' => true,
    ]);

    expect(Project::query()->firstOrFail()->technologies)->toBe(['Laravel', 'Vue']);
});

test('admin project creation rejects missing required fields', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('admin.projects.store'), [])
        ->assertSessionHasErrors(['title', 'summary']);

    $this->assertDatabaseCount('projects', 0);
});

test('admin can save a rich text experience description and skill tags', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('admin.experience.store'), [
            'company' => 'Current role',
            'role' => 'Senior Full Stack Developer',
            'start_date' => '2018-07-01',
            'description' => '<h2>Focus</h2><script>alert(1)</script><p>Built <strong>well</strong>.</p><a href="javascript:alert(1)">bad</a>',
            'skill_tags' => 'PHP, JavaScript, php, <script>C#</script>, MySQL',
            'sort_order' => 1,
        ])
        ->assertRedirect(route('admin.experience.index'));

    $experience = Experience::query()->firstOrFail();

    expect($experience->description)
        ->toContain('<h2>Focus</h2>')
        ->toContain('<strong>well</strong>')
        ->not->toContain('<script>')
        ->not->toContain('javascript:');

    expect($experience->skill_tags)->toBe(['PHP', 'JavaScript', 'C#', 'MySQL']);
});

test('admin experience form exposes skill tags as editable text', function () {
    $user = User::factory()->create();
    $experience = Experience::factory()->create([
        'skill_tags' => ['PHP', 'JavaScript'],
    ]);

    $this->actingAs($user)
        ->get(route('admin.experience.edit', $experience))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Experience/Form')
            ->where('experience.skill_tags', 'PHP, JavaScript'));
});

test('viewing a message in the admin inbox marks it as read', function () {
    $user = User::factory()->create();
    $message = ContactMessage::factory()->create(['read_at' => null]);

    $this->actingAs($user)
        ->get(route('admin.messages.show', $message))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->component('Admin/Messages/Show'));

    expect($message->refresh()->read_at)->not->toBeNull();
});

test('experience page renders the full work history', function () {
    Profile::factory()->create(['name' => 'Ben Ross']);
    Experience::factory()->create([
        'company' => 'Current role',
        'role' => 'Senior Full Stack Developer',
        'sort_order' => 1,
    ]);

    $this->get(route('experience'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Portfolio/Experience/Index')
            ->where('experience.0.company', 'Current role')
            ->where('experience.0.role', 'Senior Full Stack Developer'));
});

test('unpublished XRecipes case study is hidden from the public site', function () {
    Profile::factory()->create();
    Project::factory()->create([
        'title' => 'XRecipes',
        'slug' => 'xrecipes-independent-software-product',
        'is_published' => false,
        'is_featured' => true,
    ]);

    $this->get(route('projects.show'))->assertNotFound();

    $this->get(route('home'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->where('featuredProject', null));
});

test('project detail exposes the rich text case study', function () {
    Profile::factory()->create();
    $project = Project::factory()->create([
        'slug' => 'xrecipes-independent-software-product',
        'organization' => 'Independent project',
        'case_study_html' => '<h2>Approach</h2><p>A custom application layer.</p>',
    ]);

    $this->get(route('projects.show'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('project.organization', 'Independent project')
            ->where('project.case_study_html', '<h2>Approach</h2><p>A custom application layer.</p>'));
});

test('admin can publish or unpublish a project', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create(['is_published' => true]);

    $this->actingAs($user)
        ->put(route('admin.projects.update', $project), [
            'title' => $project->title,
            'summary' => $project->summary,
            'is_published' => false,
        ])
        ->assertRedirect(route('admin.projects.index'));

    expect($project->refresh()->is_published)->toBeFalse();
});

test('admin can save and edit the home page card image', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create();

    $this->actingAs($user)
        ->put(route('admin.projects.update', $project), [
            'title' => $project->title,
            'summary' => $project->summary,
            'card_image_url' => '/storage/images/card.png',
        ])
        ->assertRedirect(route('admin.projects.index'));

    expect($project->refresh()->card_image_url)->toBe('/storage/images/card.png');

    $this->get(route('admin.projects.edit', $project))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('project.card_image_url', '/storage/images/card.png'));
});

test('admin can update profile positioning fields', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create();

    $this->actingAs($user)
        ->put(route('admin.profile.update'), [
            'name' => $profile->name,
            'headline' => 'Senior Software Developer',
            'tagline' => 'Building practical software solutions.',
            'bio' => $profile->bio,
            'working_style' => "Independent and self-directed.\nAnalytical and solutions-oriented.",
            'education' => 'Georgian College — Computer Programming',
            'email' => $profile->email,
            'logo_url' => '/images/logo.svg',
            'headshot_url' => '/images/headshot.jpg',
            'about_image_url' => '/images/casual.jpg',
        ])
        ->assertRedirect(route('admin.profile.edit'));

    $this->assertDatabaseHas('profiles', [
        'id' => $profile->id,
        'tagline' => 'Building practical software solutions.',
        'logo_url' => '/images/logo.svg',
        'headshot_url' => '/images/headshot.jpg',
        'about_image_url' => '/images/casual.jpg',
    ]);
});

test('admin can edit the home page intro copy', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create();

    $this->actingAs($user)
        ->put(route('admin.profile.update'), [
            'name' => $profile->name,
            'headline' => $profile->headline,
            'bio' => $profile->bio,
            'email' => $profile->email,
            'hero_intro' => 'Custom introduction for the home page.',
        ])
        ->assertRedirect(route('admin.profile.edit'));

    expect($profile->refresh()->hero_intro)->toBe('Custom introduction for the home page.');

    $this->get(route('home'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('profile.hero_intro', 'Custom introduction for the home page.'));

    $this->get(route('admin.profile.edit'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('profile.hero_intro', 'Custom introduction for the home page.'));
});

test('admin can edit the home page now strip', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create();

    $this->actingAs($user)
        ->put(route('admin.profile.update'), [
            'name' => $profile->name,
            'headline' => $profile->headline,
            'bio' => $profile->bio,
            'email' => $profile->email,
            'now_building' => 'Shipping XRecipes',
            'now_learning' => 'Pest',
            'now_cooking' => 'Chili',
        ])
        ->assertRedirect(route('admin.profile.edit'));

    expect($profile->refresh()->now_building)->toBe('Shipping XRecipes')
        ->and($profile->now_learning)->toBe('Pest')
        ->and($profile->now_cooking)->toBe('Chili');

    $this->get(route('home'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('profile.now_building', 'Shipping XRecipes')
            ->where('profile.now_learning', 'Pest')
            ->where('profile.now_cooking', 'Chili'));
});

test('admin can save and edit the favicon', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create();

    $this->actingAs($user)
        ->put(route('admin.profile.update'), [
            'name' => $profile->name,
            'headline' => $profile->headline,
            'bio' => $profile->bio,
            'email' => $profile->email,
            'favicon_url' => '/storage/images/favicon.png',
        ])
        ->assertRedirect(route('admin.profile.edit'));

    expect($profile->refresh()->favicon_url)->toBe('/storage/images/favicon.png');

    $this->get(route('admin.profile.edit'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('profile.favicon_url', '/storage/images/favicon.png'));
});

test('admin sanitizes outside the code rich text', function () {
    $user = User::factory()->create();
    $profile = Profile::factory()->create();

    $this->actingAs($user)
        ->put(route('admin.profile.update'), [
            'name' => $profile->name,
            'headline' => $profile->headline,
            'bio' => $profile->bio,
            'email' => $profile->email,
            'outside_the_code_html' => '<h2>Outside</h2><script>alert(1)</script><p>Running and <strong>reading</strong>.</p><a href="javascript:alert(1)">bad</a>',
        ])
        ->assertRedirect(route('admin.profile.edit'));

    $cleaned = $profile->refresh()->outside_the_code_html;

    expect($cleaned)
        ->toContain('<h2>Outside</h2>')
        ->toContain('<p>Running and <strong>reading</strong>.</p>')
        ->not->toContain('<script')
        ->not->toContain('javascript:');

    $this->get(route('admin.profile.edit'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('profile.outside_the_code_html', $cleaned));
});

test('admin can upload an inline outside the code image', function () {
    Storage::fake('public');
    $user = User::factory()->create();

    $this->actingAs($user)
        ->from(route('admin.profile.edit'))
        ->post(route('admin.images.store'), [
            'field' => 'outside_the_code_image',
            'image' => UploadedFile::fake()->image('outside.png'),
        ])
        ->assertRedirect(route('admin.profile.edit'));

    expect(session('uploaded_field'))->toBe('outside_the_code_image');
});

test('public pages use the configured favicon', function () {
    Profile::factory()->create(['favicon_url' => '/storage/images/favicon.png']);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('<link rel="icon" href="/storage/images/favicon.png">', false)
        ->assertSee('<link rel="apple-touch-icon" href="/storage/images/favicon.png">', false);
});

test('public pages keep the default favicon when none is configured', function () {
    Profile::factory()->create(['favicon_url' => null]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('<link rel="icon" href="/favicon.ico" sizes="any">', false)
        ->assertSee('<link rel="icon" href="/favicon.svg" type="image/svg+xml">', false);
});

test('admin can upload a favicon image', function () {
    Storage::fake('public');
    $user = User::factory()->create();

    $this->actingAs($user)
        ->from(route('admin.profile.edit'))
        ->post(route('admin.images.store'), [
            'field' => 'favicon_url',
            'image' => UploadedFile::fake()->image('favicon.png'),
        ])
        ->assertRedirect(route('admin.profile.edit'));

    $url = session('uploaded_url');

    expect(session('uploaded_field'))->toBe('favicon_url');
    expect($url)->toStartWith('/storage/images/');
    Storage::disk('public')->assertExists(Str::after($url, '/storage/'));
});

test('public pages expose the profile image slots', function () {
    Profile::factory()->create([
        'headshot_url' => '/images/headshot.jpg',
        'about_image_url' => '/images/casual.jpg',
    ]);

    $this->get(route('home'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('profile.headshot_url', '/images/headshot.jpg'));

    $this->get(route('about'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('profile.about_image_url', '/images/casual.jpg'));
});

test('admin can upload an image for a portfolio field', function () {
    Storage::fake('public');
    $user = User::factory()->create();

    $this->actingAs($user)
        ->from(route('admin.profile.edit'))
        ->post(route('admin.images.store'), [
            'field' => 'headshot_url',
            'image' => UploadedFile::fake()->image('headshot.jpg'),
        ])
        ->assertRedirect(route('admin.profile.edit'));

    $url = session('uploaded_url');

    expect($url)->toStartWith('/storage/images/');
    expect(Storage::disk('public')->url(Str::after($url, '/storage/')))->toBe($url);
    Storage::disk('public')->assertExists(Str::after($url, '/storage/'));
});

test('admin can upload an inline case study image', function () {
    Storage::fake('public');
    $user = User::factory()->create();

    $this->actingAs($user)
        ->from(route('admin.projects.index'))
        ->post(route('admin.images.store'), [
            'field' => 'case_study_image',
            'image' => UploadedFile::fake()->image('diagram.png'),
        ])
        ->assertRedirect(route('admin.projects.index'));

    $url = session('uploaded_url');

    expect(session('uploaded_field'))->toBe('case_study_image');
    expect($url)->toStartWith('/storage/images/');
    Storage::disk('public')->assertExists(Str::after($url, '/storage/'));
});

test('admin can upload a home page card image', function () {
    Storage::fake('public');
    $user = User::factory()->create();

    $this->actingAs($user)
        ->from(route('admin.projects.index'))
        ->post(route('admin.images.store'), [
            'field' => 'card_image_url',
            'image' => UploadedFile::fake()->image('card.png'),
        ])
        ->assertRedirect(route('admin.projects.index'));

    $url = session('uploaded_url');

    expect(session('uploaded_field'))->toBe('card_image_url');
    expect($url)->toStartWith('/storage/images/');
    Storage::disk('public')->assertExists(Str::after($url, '/storage/'));
});

test('admin can upload an inline experience description image', function () {
    Storage::fake('public');
    $user = User::factory()->create();

    $this->actingAs($user)
        ->from(route('admin.experience.index'))
        ->post(route('admin.images.store'), [
            'field' => 'description_image',
            'image' => UploadedFile::fake()->image('diagram.png'),
        ])
        ->assertRedirect(route('admin.experience.index'));

    expect(session('uploaded_field'))->toBe('description_image');
    expect(session('uploaded_url'))->toStartWith('/storage/images/');
});

test('admin sanitizes rich text case studies', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create();

    $this->actingAs($user)
        ->put(route('admin.projects.update', $project), [
            'title' => $project->title,
            'summary' => $project->summary,
            'case_study_html' => '<h2>Approach</h2><script>alert(1)</script><p>Built <strong>well</strong>.</p><a href="javascript:alert(1)">bad</a><img src="/storage/images/diagram.png" alt="Architecture diagram"><img src="javascript:alert(1)"><img src="data:text/html;base64,PHNjcmlwdD4=">',
        ])
        ->assertRedirect(route('admin.projects.index'));

    $html = $project->refresh()->case_study_html;

    expect($html)
        ->toContain('<h2>Approach</h2>')
        ->toContain('<strong>well</strong>')
        ->toContain('<img src="/storage/images/diagram.png" alt="Architecture diagram">')
        ->not->toContain('<script>')
        ->not->toContain('javascript:')
        ->not->toContain('data:text/html');
});
