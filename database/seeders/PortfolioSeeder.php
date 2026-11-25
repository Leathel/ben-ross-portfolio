<?php

namespace Database\Seeders;

use App\Models\Experience;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Skill;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PortfolioSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the editable portfolio content.
     *
     * All of this content can be edited from the authenticated admin area.
     */
    public function run(): void
    {
        $profile = Profile::query()->first() ?? new Profile;
        $profile->fill([
            'name' => 'Ben Ross',
            'headline' => 'Senior Full Stack Developer',
            'tagline' => 'Backend engineering, application architecture and systems integration',
            'hero_intro' => 'Senior Full Stack Developer. I take complex problems from discovery through to implementation, with a focus on backend systems, integrations and practical business impact.',
            'now_building' => 'XRecipes, with the goal of getting real creators posting',
            'now_learning' => 'automated testing in PHP with Pest',
            'now_cooking' => '[TODO: Add your current recipe, e.g. the perfect slow-cooker chili]',
            'bio' => "I'm a software developer with over 10 years of experience designing, building and maintaining custom software applications, backend systems and web platforms.\n\nMy career has taken me through several areas of software development, from infrastructure automation and financial systems to custom web applications and integrations.\n\nIn my current role as a Senior Full Stack Developer, I work primarily with PHP, JavaScript, SQL and WordPress, and I care about maintainable architecture, thoughtful technical decisions and practical outcomes.\n\nOutside of my professional work, I independently conceptualized and developed XRecipes, a recipe-sharing platform I designed, built and run solo. It gave me room to explore product ownership, application architecture, database design, infrastructure and end-to-end software delivery.\n\nI believe good software development is about more than writing code. It's about understanding the problem, making considered decisions and delivering software that works well for the people using it.",
            'working_style' => "Independent and self-directed.\nAnalytical and solutions-oriented.\nComfortable owning projects from discovery through deployment.\nInterested in continuous improvement and emerging technologies.\nEnjoys simplifying complicated processes through automation.\nComfortable communicating with both technical and non-technical stakeholders.\nValues maintainable architecture, practical solutions and thoughtful outcomes.",
            'education' => "Georgian College — Computer Programming\nSeptember 2013 – September 2016\n\nCompleted three four-month government co-op placements as part of the program.",
            'outside_the_code_html' => '[TODO: Add things you do outside of work.]',
            'location' => 'Ontario, Canada',
            'email' => 'hello@benross.ca',
            'phone' => null,
            'website_url' => null,
            'github_url' => 'https://github.com',
            'linkedin_url' => 'https://www.linkedin.com',
            'available_for_work' => false,
            'years_experience' => 10,
            'resume_url' => null,
            'logo_url' => '/storage/images/5cMCdwPi0eijJPJJQQ7HG4cmEFA81j8YoiKAcGzo.png',
            'headshot_url' => '/storage/images/jqj9519s0Q5XRUQncsjtAiGOiVqgUC8R4UPA3dXD.jpg',
            'about_image_url' => '/storage/images/gNzIo7W30xfiDlZjjBDaYAvjrqTxDSDltAKATqvN.jpg',
        ])->save();

        $xrecipesCaseStudy = <<<'HTML'
<p><strong>XRecipes: a recipe-sharing platform I built solo</strong></p>
<p><strong>Role:</strong> Designer and sole developer · <strong>Status:</strong> Launched, early stage<br>
<strong>Stack:</strong> PHP 8.5+, MySQL, JavaScript, WordPress, OpenAI API, Cloudflare R2, GitHub Actions</p>
<p>XRecipes is a recipe-sharing site where people publish recipes, build collections and follow what other cooks are making. I designed, built and run all of it. I worked on it for about two years and launched publicly in November 2026. It's early: the core product works, and my focus now is getting real people using it and learning from them.</p><p><img src="/storage/images/zXeO6FW0FqDNTFH37VCCKEdXbWRtH9rFTYziYCf0.png"><br></p>
<p><strong>Why I built it</strong></p>
<p>I cook a lot, and I got tired of recipe sites that make you get through a life story, a few popups and an autoplay video before you reach the ingredients. Recipes are also scattered across blogs, screenshots and social posts. I wanted one clean, fast place where the recipe comes first and creators can build a library of their own.</p>
<p><strong>What it does</strong></p>
<p>Users can publish recipes with photos, organize them into collections, save and comment on other people's recipes, and keep a personal cookbook. Two AI features cut the effort of contributing: an importer that turns pasted text or a social caption into a structured recipe, and a nutrition estimator that works from the ingredient list.</p>
<p><strong>How it's built</strong></p>
<p>The most interesting decision was how I used WordPress. I use it for what it already does well: accounts, authentication, roles, routing and REST infrastructure. The product itself doesn't use WordPress posts at all. In fact, it only has 5 plugins, which is remarkably few. Recipes, ingredients, collections, favourites and follows live in a custom relational MySQL schema with its own indexes and transactions, exposed through about 39 custom REST routes. Recipes and ingredients are a many-to-many relationship, not a blog post, so this let me model the domain properly without rebuilding login and routing from scratch, which mattered as a solo developer.</p><ul><li><strong>Hybrid rendering.</strong> Pages render server-side in PHP for fast loads and SEO. JavaScript handles live search, filtering, infinite scroll, ingredient autocomplete and quick-view modals.</li><li><strong>Custom search.</strong> SQL-based search built around the data model, with multi-term matching, ingredient and dietary filters and popularity ranking, so I control how results are ranked.</li><li><strong>AI features.</strong> The importer uses schema-constrained structured outputs, so responses are validated before they reach the database. Nutrition figures are estimates based on USDA FoodData Central conventions. OpenAI's moderation API screens submitted text and images.</li><li><strong>Media.</strong> Uploads go to Cloudflare R2 through presigned URLs, with validation, resizing, WebP conversion and CDN delivery, which keeps user images off the main host.</li><li><strong>Deployment and security.</strong> GitHub Actions handles deploys, and database migrations run in transactions. The API is rate limited, with authentication checks and input sanitization throughout, and I've ran automated vulnerability scans alongside manual security testing.</li><li><strong>Caching with transients.</strong> Anything expensive or repeated is cached with WordPress transients: responses from repeated external API calls, repeated AI requests (identical input returns the stored result instead of another OpenAI call), and shared page elements like the header and common components, which are rendered once and reused. [TODO: Add how you set expiry and invalidate cache transients, e.g. cache keys, TTLs, clearing on publish. Interviewers will ask.]</li><li><strong>Security and privacy.</strong> Beyond the WordPress hardening, the server strips HTML from user-submitted content, the R2 bucket can't be listed, no API keys or secrets ship in the front-end JavaScript, outbound email is protected with DMARC set to quarantine, and analytics wait for cookie consent.</li></ul><p><strong>What WordPress cost me</strong></p>
<p>WordPress was the right call for a solo build, but its defaults need attention on every project, so I hardened them deliberately:</p><ul><li><strong>Exposed user data.</strong> Core REST endpoints can reveal account information, which matters when the whole product is user accounts. I locked them down, so anonymous requests to the user and settings endpoints return 401, and so do the endpoints for a user's recipes and collections, even when given a user ID. Anonymous recipe creation, favourites, upload URLs, plugin admin routes, and all the AI endpoints require a login, and /wp-admin/ and the users screen redirect to the login page. Sensitive paths like .env, .git and debug logs return 403.</li><li><strong>Security headers and CSP.</strong> WordPress leans on inline scripts and styles, which makes a fully strict Content Security Policy hard. I deployed the controls that don't depend on that: TLS 1.2/1.3 only, HSTS, nosniff, X-Frame-Options DENY, a Permissions-Policy, and a CSP with frame-ancestors, object-src and base-uri all set to none. Cookies are Secure and HttpOnly. </li><li><strong>Perception.</strong> Some developers write WordPress off as a blogging tool. I used it for accounts, auth, routing and REST, and kept the product itself out of its content model, so the choice rests on engineering.</li></ul><p><strong>Designed to scale, with room to grow</strong></p>
<p>I built with growth in mind from the start. Media lives on Cloudflare R2 behind the CDN, and uploads use presigned URLs, so images never pass through the main server. Caching works in layers: Cloudflare's edge, the host's dynamic cache, and application-level transients for API calls, AI requests and shared page elements. The API is rate limited to protect resource-intensive endpoints from abuse and unexpected traffic spikes. AI processing currently happens through dedicated API requests, with transient caching to avoid repeating expensive operations. Deployment is automated through GitHub Actions, and the hosting provider handles daily backups.</p><p>I haven't load tested at scale yet, so my next step is to establish performance baselines and measure where the application starts to reach its limits.</p><p></p><p><strong>What I'd do differently</strong></p>
<p>I'd launch sooner. I spent a long time refining the architecture before a single real user touched it, and the decisions that matter most only became clear once people used it. Now that it's live, I'm watching real usage to decide where to invest next.</p><p><strong>Where it stands</strong></p>
<p>The core platform is live, with over 60 recipes from about a dozen creators. Next up:</p><ul><li>Easier importing from recipe URLs, social links and photos of recipe cards</li><li>Creator following, notifications and a personalized feed</li><li>Automated sharing to Pinterest and Reddit to bring in new visitors</li><li>Continued work on search, performance, onboarding and moderation</li></ul>
HTML;

        $projects = [
            [
                'title' => 'XRecipes',
                'slug' => 'xrecipes-independent-software-product',
                'summary' => 'A recipe-sharing platform I designed, built and run solo. Launched November 2026, still early stage.',
                'description' => 'XRecipes is a recipe-sharing platform I designed, built and run solo. Launched November 2026, still early stage.',
                'category' => 'Independent Project',
                'organization' => 'Independent project',
                'problem' => 'Recipe websites are often cluttered with advertising and irrelevant content, making it frustrating to find, save and use a recipe. I wanted to explore what a focused recipe platform could look like when designed around the actual task.',
                'role' => 'Solo Developer',
                'approach' => "Rather than relying on standard content functionality, I built a custom application layer using WordPress for user management, authentication, routing and core platform functionality. Application data lives in custom relational SQL tables for recipes, ingredients, collections and interactions, with approximately 39 custom REST route definitions across two API namespaces. The frontend combines server-rendered PHP templates with JavaScript-driven interactions, search, filtering, pagination and content loading.\n\nOpenAI APIs support an AI recipe importer that extracts structured recipe data from unstructured text, captions, emails and URLs; a nutrition calculator that estimates calories, protein, carbohydrates, fats, fibre, sugar and sodium using schema-constrained outputs aligned with USDA FoodData Central conventions; and multimodal moderation of submitted text and images.",
                'challenges' => 'Delivering a full product independently meant owning architecture, database design, AI integration, media handling, deployment, caching, rate limiting, validation, permissions and content moderation. Cloudflare R2 and CDN handle object storage and delivery, while GitHub Actions manages deployment and database migrations run with transaction handling.',
                'results' => 'Delivered an end-to-end product from concept and branding through architecture, implementation, infrastructure and launch, with automated image processing, deployment pipelines and security testing.',
                'technologies' => ['PHP', 'WordPress', 'MySQL', 'JavaScript', 'OpenAI API', 'Cloudflare R2'],
                'live_url' => 'https://xrecipes.ca',
                'source_url' => null,
                'image_url' => '/storage/images/PmnZZdwJFmKeqBTsyOcshWcdABRawgJ8gHy1LZUK.png',
                'case_study_html' => $xrecipesCaseStudy,
                'is_featured' => true,
                'is_published' => true,
                'sort_order' => 1,
            ],
        ];

        $projectIds = [];

        foreach ($projects as $project) {
            $projectIds[] = Project::query()->updateOrCreate(
                ['slug' => $project['slug']],
                $project,
            )->getKey();
        }

        Project::query()->whereNotIn('id', $projectIds)->delete();

        $skills = [
            'Languages' => ['PHP', 'JavaScript', 'C#', 'SQL'],
            'Web and Platforms' => [
                'WordPress (custom themes, plugins, ACF)', 'Shopify', 'HTML/CSS',
            ],
            'Backend and Architecture' => [
                'REST API design', 'Third-party integrations', 'Application architecture',
                'MySQL and database design', 'Authentication and security',
                'Background processing and automation', 'OpenAI API integration',
            ],
            'Infrastructure and Tooling' => [
                'Linux', 'Git and GitHub Actions CI/CD', 'Cloudflare (CDN, R2)',
            ],
        ];

        $skillIds = [];
        $sortOrder = 1;

        foreach ($skills as $category => $categorySkills) {
            foreach ($categorySkills as $name) {
                $skillIds[] = Skill::query()->updateOrCreate(
                    ['name' => $name, 'category' => $category],
                    ['proficiency' => null, 'sort_order' => $sortOrder++],
                )->getKey();
            }
        }

        Skill::query()->whereNotIn('id', $skillIds)->delete();

        $experiences = [
            [
                'company' => 'Current role',
                'role' => 'Senior Full Stack Developer',
                'location' => 'Ontario, Canada',
                'employment_type' => 'Full-time',
                'start_date' => '2018-07-01',
                'end_date' => null,
                'is_current' => true,
                'description' => '<p>Work across client web platforms, custom application functionality and integrations. Lead technical discovery, planning and delivery, troubleshoot complex issues, provide client guidance, and take part in developer interviews, onboarding and mentoring.</p>',
                'skill_tags' => ['PHP', 'JavaScript', 'SQL', 'WordPress', 'Integrations', 'Technical discovery', 'Mentoring'],
                'sort_order' => 1,
            ],
            [
                'company' => 'ShopEddies',
                'role' => 'Software Developer',
                'location' => 'Ontario, Canada',
                'employment_type' => 'Full-time',
                'start_date' => '2016-01-01',
                'end_date' => '2018-06-30',
                'is_current' => false,
                'description' => '<p>Independently developed a C# financial reconciliation and reporting application, inventory aggregation and product enrichment systems, and a Shopify storefront. Built automated reporting workflows, provided occasional IT support and mentored co-op students.</p>',
                'skill_tags' => ['C#', 'Shopify', 'Reporting', 'Inventory systems', 'Automation', 'Mentoring'],
                'sort_order' => 2,
            ],
            [
                'company' => 'Ontario Ministry of Government and Consumer Services',
                'role' => 'Systems Analyst Co-op',
                'location' => 'Ontario, Canada',
                'employment_type' => 'Co-op',
                'start_date' => '2014-05-01',
                'end_date' => '2014-09-30',
                'is_current' => false,
                'description' => '<p>Developed C# infrastructure automation software for an infrastructure team. Automated bulk server health checks, DNS lookups and remote command execution. Assisted with infrastructure monitoring, server provisioning and monitoring software configuration.</p>',
                'skill_tags' => ['C#', 'Infrastructure automation', 'DNS', 'Server provisioning', 'Monitoring'],
                'sort_order' => 3,
            ],
            [
                'company' => 'Ontario Ministry of Government and Consumer Services',
                'role' => 'Systems Analyst Co-op',
                'location' => 'Ontario, Canada',
                'employment_type' => 'Co-op',
                'start_date' => '2015-01-01',
                'end_date' => '2015-04-30',
                'is_current' => false,
                'description' => '<p>Continued working with the infrastructure team. Supported server provisioning, monitoring and operational troubleshooting, and worked with enterprise infrastructure monitoring tools and server management processes.</p>',
                'skill_tags' => ['Server provisioning', 'Monitoring', 'Troubleshooting', 'Infrastructure'],
                'sort_order' => 4,
            ],
            [
                'company' => 'Ontario Ministry of Government and Consumer Services',
                'role' => 'Business Analyst Co-op',
                'location' => 'Ontario, Canada',
                'employment_type' => 'Co-op',
                'start_date' => '2015-09-01',
                'end_date' => '2015-12-31',
                'is_current' => false,
                'description' => '<p>Researched and evaluated an enterprise survey platform, gathered stakeholder requirements, coordinated testing, configuration and rollout, and researched Elasticsearch using real datasets. Prepared and delivered a technical presentation and created brand guideline documentation for public-facing government websites and applications.</p>',
                'skill_tags' => ['Requirements gathering', 'Testing and rollout', 'Elasticsearch', 'Technical presentation'],
                'sort_order' => 5,
            ],
        ];

        $experienceIds = [];

        foreach ($experiences as $experience) {
            $experienceIds[] = Experience::query()->updateOrCreate(
                [
                    'company' => $experience['company'],
                    'role' => $experience['role'],
                    'start_date' => $experience['start_date'],
                ],
                $experience,
            )->getKey();
        }

        Experience::query()->whereNotIn('id', $experienceIds)->delete();
    }
}
