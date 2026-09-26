(function () {
    "use strict";

    var root = document.documentElement;
    root.classList.add("js");

    var reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    var finePointer = window.matchMedia("(hover: hover) and (pointer: fine)").matches;
    var $$ = function (sel, ctx) { return Array.prototype.slice.call((ctx || document).querySelectorAll(sel)); };

    // Theme: dark by default, remember an explicit choice
    try {
        var saved = localStorage.getItem("theme");
        if (saved === "light" || saved === "dark") root.setAttribute("data-theme", saved);
    } catch (e) {}

    document.querySelector(".theme-toggle").addEventListener("click", function () {
        var next = root.getAttribute("data-theme") === "light" ? "dark" : "light";
        root.setAttribute("data-theme", next);
        try { localStorage.setItem("theme", next); } catch (e) {}
    });

    document.getElementById("year").textContent = new Date().getFullYear();

    // Ask Wasim AI: a private, résumé-grounded assistant that runs entirely in the browser
    var aiOpen = document.querySelector("[data-ai-open]");
    var aiPanel = document.getElementById("ai-assistant");
    var aiClose = document.querySelector("[data-ai-close]");
    var aiMessages = document.querySelector("[data-ai-messages]");
    var aiForm = document.querySelector("[data-ai-form]");
    var aiInput = document.querySelector("[data-ai-input]");

    if (aiOpen && aiPanel && aiMessages && aiForm && aiInput) {
        var aiKnowledge = [
            {
                patterns: ["who is wasim", "about wasim", "tell me about", "summary", "profile", "background"],
                answer: "Muhammad Wasim Arshad is an Engineering Manager and Software Architect in Lahore with 16+ years of experience. He combines engineering leadership, healthcare domain expertise and pragmatic architecture to modernize dependable SaaS products.",
                links: [{ label: "Read the profile", href: "#about" }]
            },
            {
                patterns: ["current role", "current job", "work now", "icaremanager", "team", "teams", "leadership"],
                answer: "Wasim is an Engineering Manager at iCareManager. He leads four cross-functional product teams and owns technical strategy, architecture, delivery planning, engineering quality and modernization across healthcare SaaS products.",
                links: [{ label: "View experience", href: "#experience" }]
            },
            {
                patterns: ["healthcare", "ehr", "emr", "emar", "medical billing", "scheduling", "workforce", "domain"],
                answer: "Wasim’s healthcare experience spans EHR/EMR, eMAR, medical billing, workforce management, scheduling, appointments, training and provider management. He has led modernization of legacy eMAR capabilities using modern services and scalable architecture.",
                links: [{ label: "See healthcare expertise", href: "#skills" }]
            },
            {
                patterns: ["architecture", "system design", "microservices", "clean architecture", "cqrs", "event driven", "modular monolith", "pub sub", "scalability"],
                answer: "His architecture toolkit includes microservices, modular monoliths, Clean Architecture, CQRS, event-driven systems, Pub/Sub and REST APIs. He selects patterns around product constraints, delivery risk, maintainability and scale.",
                links: [{ label: "Explore architecture skills", href: "#skills" }]
            },
            {
                patterns: ["ai", "artificial intelligence", "generative ai", "agentic", "automation", "ai assisted"],
                answer: "Wasim applies AI-assisted engineering across requirements analysis, solution design, implementation, testing, code review and documentation. His focus is practical use with people accountable for every product and architecture decision.",
                links: [{ label: "See AI capabilities", href: "#skills" }]
            },
            {
                patterns: ["cloud", "aws", "lambda", "dynamodb", "serverless", "devops", "database"],
                answer: "His cloud and data experience includes AWS, Lambda, DynamoDB, SQL, MySQL, serverless services, database scalability, deployments and DevOps. He built a serverless messaging and commenting service for healthcare workflows.",
                links: [{ label: "View selected impact", href: "#testimonials" }]
            },
            {
                patterns: ["technology", "technologies", "tech stack", "programming", "frontend", "backend", "mobile", "framework", "language"],
                answer: "Wasim has worked with .NET, C#, Angular, AngularJS, React, React Native, Node.js, Ruby on Rails, PHP, JavaScript, Ionic, REST APIs, AWS, DynamoDB, SQL and MySQL.",
                links: [{ label: "Browse the full skill set", href: "#skills" }]
            },
            {
                patterns: ["experience", "career", "work history", "companies", "years"],
                answer: "Wasim has 16+ years of experience across engineering and leadership roles at iCareManager, Credibal, Hashe Computer Solutions, Fortsolution and ApniMarket.pk. His progression spans Software Engineer through Staff Engineer, Principal Engineer and Engineering Manager.",
                links: [{ label: "See career history", href: "#experience" }]
            },
            {
                patterns: ["education", "degree", "university", "certification", "qualification", "bzu", "lumx"],
                answer: "Wasim holds a Master of Computer Science from Bahauddin Zakariya University and a bachelor’s degree focused on Mathematics and Physics. He also completed a Project Management Program at LUMX in 2025.",
                links: [{ label: "View education", href: "#education" }]
            },
            {
                patterns: ["contact", "email", "linkedin", "github", "hire", "hiring", "opportunity", "available", "reach"],
                answer: "You can reach Wasim by email or connect on LinkedIn. He is open to conversations about engineering leadership, healthcare products, software architecture and modernization challenges.",
                links: [
                    { label: "Email Wasim", href: "mailto:chouhdarywasim@gmail.com" },
                    { label: "Open LinkedIn", href: "https://www.linkedin.com/in/wasim-arshad-software-architect/", external: true }
                ]
            },
            {
                patterns: ["resume", "résumé", "cv", "download"],
                answer: "Wasim’s résumé includes his complete career history, technical skills, leadership experience and education.",
                links: [{ label: "Download the résumé", href: "books/wasim_arshad.pdf", external: true }]
            },
            {
                patterns: ["location", "where", "lahore", "pakistan", "timezone"],
                answer: "Wasim is based in Lahore, Pakistan, in Pakistan Standard Time (UTC+5).",
                links: [{ label: "View profile details", href: "#about" }]
            }
        ];

        var normalize = function (text) {
            return text.toLowerCase().replace(/[’']/g, "").replace(/[^a-z0-9+#.]+/g, " ").trim();
        };
        var stopWords = { "a": 1, "an": 1, "and": 1, "are": 1, "about": 1, "can": 1, "does": 1, "for": 1, "has": 1, "his": 1, "how": 1, "is": 1, "me": 1, "of": 1, "the": 1, "to": 1, "wasim": 1, "what": 1, "with": 1 };

        var findAnswer = function (question) {
            var normalized = normalize(question);
            var words = normalized.split(/\s+/).filter(function (word) { return word && !stopWords[word]; });
            var best = null, bestScore = 0;
            aiKnowledge.forEach(function (item) {
                var score = 0;
                item.patterns.forEach(function (pattern) {
                    var p = normalize(pattern);
                    if (normalized.indexOf(p) !== -1) score += 5 + p.split(" ").length;
                    p.split(" ").forEach(function (word) {
                        if (!stopWords[word] && words.indexOf(word) !== -1) score += 1;
                    });
                });
                if (score > bestScore) { bestScore = score; best = item; }
            });
            return bestScore >= 2 ? best : {
                answer: "I can help with Wasim’s current role, healthcare domain experience, architecture, AI-assisted engineering, cloud skills, career history, education, résumé or contact details. Try one of the suggested questions below.",
                links: [{ label: "Explore the full profile", href: "#about" }]
            };
        };

        var addMessage = function (text, role, links) {
            var message = document.createElement("div");
            message.className = "ai-message " + role;
            message.textContent = text;
            (links || []).forEach(function (link) {
                var a = document.createElement("a");
                a.href = link.href;
                a.textContent = link.label;
                if (link.external) { a.target = "_blank"; a.rel = "noopener"; }
                message.appendChild(document.createElement("br"));
                message.appendChild(a);
            });
            aiMessages.appendChild(message);
            aiMessages.scrollTop = aiMessages.scrollHeight;
        };

        var ask = function (question) {
            var clean = question.trim();
            if (!clean) return;
            addMessage(clean, "user");
            aiInput.value = "";
            var result = findAnswer(clean);
            window.setTimeout(function () { addMessage(result.answer, "bot", result.links); }, reduceMotion ? 0 : 180);
        };

        var openAssistant = function () {
            aiPanel.hidden = false;
            aiOpen.setAttribute("aria-expanded", "true");
            window.setTimeout(function () { aiInput.focus(); }, 0);
        };
        var closeAssistant = function () {
            aiPanel.hidden = true;
            aiOpen.setAttribute("aria-expanded", "false");
            aiOpen.focus();
        };

        aiOpen.addEventListener("click", function () { aiPanel.hidden ? openAssistant() : closeAssistant(); });
        aiClose.addEventListener("click", closeAssistant);
        aiForm.addEventListener("submit", function (event) { event.preventDefault(); ask(aiInput.value); });
        $$("[data-ai-prompt]").forEach(function (button) {
            button.addEventListener("click", function () { ask(button.dataset.aiPrompt); });
        });
        document.addEventListener("keydown", function (event) {
            if (event.key === "Escape" && !aiPanel.hidden) closeAssistant();
        });
    }

    // Split headings into words for the rise-in animation
    $$(".split").forEach(function (el) {
        var words = el.textContent.trim().split(/\s+/);
        el.setAttribute("aria-label", el.textContent.trim());
        el.innerHTML = words.map(function (w, i) {
            return '<span class="w" aria-hidden="true"><span style="--wi:' + i + '">' + w.replace(/&/g, "&amp;").replace(/</g, "&lt;") + "</span></span>";
        }).join(" ");
    });

    // Skill card index numbers
    $$(".skill-card[data-index]").forEach(function (el) {
        var n = document.createElement("span");
        n.className = "index";
        n.setAttribute("aria-hidden", "true");
        n.textContent = el.dataset.index;
        el.appendChild(n);
    });

    // Duplicate marquee items so the loop is seamless
    $$(".marquee-track").forEach(function (track) {
        $$("li", track).forEach(function (li) {
            var clone = li.cloneNode(true);
            clone.setAttribute("aria-hidden", "true");
            track.appendChild(clone);
        });
    });

    // Stagger reveal delays inside grids
    $$(".bento, .skills-grid, .edu-grid, .quotes, .about-grid").forEach(function (group) {
        $$(".reveal", group).forEach(function (el, i) { el.style.setProperty("--i", i); });
    });

    // Live Lahore clock
    var clock = document.querySelector("[data-clock]");
    if (clock) {
        var fmt = new Intl.DateTimeFormat("en-GB", { timeZone: "Asia/Karachi", hour: "2-digit", minute: "2-digit", hour12: false });
        var tick = function () {
            var parts = fmt.format(new Date()).split(":");
            clock.innerHTML = parts[0] + '<span class="colon">:</span>' + parts[1];
        };
        tick();
        setInterval(tick, 15000);
    }

    // Scroll-linked: header border, progress bar, timeline fill
    var header = document.querySelector(".site-header");
    var progress = document.querySelector(".scroll-progress");
    var timeline = document.querySelector(".timeline");
    var fill = document.querySelector(".timeline-fill");
    var jobs = $$(".job");
    var ticking = false;

    function onScroll() {
        var y = window.scrollY;
        var max = document.documentElement.scrollHeight - window.innerHeight;
        header.classList.toggle("scrolled", y > 8);
        progress.style.transform = "scaleX(" + (max > 0 ? y / max : 0) + ")";

        if (timeline) {
            var r = timeline.getBoundingClientRect();
            var mark = window.innerHeight * 0.6;
            var f = Math.min(Math.max((mark - r.top) / r.height, 0), 1);
            fill.style.setProperty("--fill", f.toFixed(4));
            jobs.forEach(function (job) {
                job.classList.toggle("lit", job.getBoundingClientRect().top + 24 < mark);
            });
        }
        ticking = false;
    }
    window.addEventListener("scroll", function () {
        if (!ticking) { ticking = true; requestAnimationFrame(onScroll); }
    }, { passive: true });
    window.addEventListener("resize", onScroll);
    onScroll();

    // Showcase tile: loops my dev workflow, an agentic automation run, and its flow diagram
    var termTile = document.querySelector(".t-term");
    if (termTile) {
        var AUTOMATION_STEPS = ["Discover", "Design", "Plan", "Build", "Verify", "Evolve"];
        var scenarios = [
            {
                type: "term",
                title: "architecture — healthcare-platform",
                tag: "Modernization workflow",
                steps: ["Assess", "Map", "Design", "Build", "Verify", "Release"],
                lines: [
                    { cmd: 'assess "legacy eMAR modernization"', step: 0 },
                    { out: "✓ workflows, dependencies and risks mapped", k: "ok" },
                    { cmd: "define target-architecture", step: 1 },
                    { out: "✓ service boundaries and data flows agreed", k: "ok" },
                    { cmd: "plan incremental-migration", step: 2 },
                    { out: "✓ roadmap aligned with product priorities", k: "ok" },
                    { out: "● teams delivering modernization slices …", k: "info", step: 3 },
                    { out: "✓ quality gates and regression checks passed", k: "ok", step: 4 },
                    { out: "✓ capability released with continuity", k: "ok", step: 5 }
                ]
            },
            {
                type: "term",
                title: "delivery — ai-assisted-sdlc",
                tag: "AI-enabled engineering",
                steps: AUTOMATION_STEPS,
                lines: [
                    { cmd: "analyze product-requirements", step: 0 },
                    { out: "✓ ambiguity and edge cases highlighted", k: "ok" },
                    { out: "✓ solution options drafted for review", k: "ok", step: 1 },
                    { out: "✓ delivery plan and risks documented", k: "ok", step: 2 },
                    { out: "● implementation supported by AI assistance …", k: "info", step: 3 },
                    { out: "✓ tests, code review and documentation complete", k: "ok", step: 4 },
                    { out: "✓ team feedback captured for the next cycle", k: "ok", step: 5 },
                    { out: "  people remain accountable for every decision", k: "dim" }
                ]
            },
            {
                type: "flow",
                title: "workflow — product-engineering",
                tag: "Delivery flow",
                steps: AUTOMATION_STEPS
            }
        ];

        // Flow diagram: node centres for a wide (h) and a stacked phone (v) layout
        var FLOW = {
            nodes: [
                { id: "email", label: "Customers", h: [70, 70], v: [62, 24] },
                { id: "form", label: "Product", h: [70, 135], v: [170, 24] },
                { id: "api", label: "Operations", h: [70, 200], v: [278, 24] },
                { id: "orch", label: "Strategy", h: [215, 135], v: [170, 110], w: 112, cls: "agent" },
                { id: "extract", label: "Architecture", h: [375, 70], v: [62, 196], w: 120, cls: "agent" },
                { id: "validate", label: "Roadmap", h: [375, 135], v: [170, 196], w: 120, cls: "agent" },
                { id: "browser", label: "Teams", h: [375, 200], v: [278, 196], w: 120, cls: "agent" },
                { id: "review", label: "Quality", h: [530, 135], v: [170, 282], w: 104, cls: "agent" },
                { id: "erp", label: "Release", h: [656, 70], v: [62, 368], w: 112 },
                { id: "notify", label: "Telemetry", h: [656, 135], v: [170, 368], w: 112 },
                { id: "human", label: "Feedback", h: [656, 200], v: [278, 368], w: 112, cls: "warn" }
            ],
            edges: [
                ["email", "orch"], ["form", "orch"], ["api", "orch"],
                ["orch", "extract"], ["orch", "validate"], ["orch", "browser"],
                ["extract", "review"], ["validate", "review"], ["browser", "review"],
                ["review", "erp"], ["review", "notify"], ["review", "human", "warn"]
            ],
            cols: [["INPUTS", 70], ["ALIGN", 215], ["DELIVER", 375], ["VERIFY", 530], ["OUTCOMES", 656]],
            steps: [
                { pre: ["email", "form", "api"], edges: [["email", "orch"], ["form", "orch"], ["api", "orch"]], post: ["orch"], text: ["info", "● customer, product and operational needs aligned"] },
                { edges: [["orch", "extract"]], post: ["extract"], text: ["ok", "✓ architecture responds to product constraints"] },
                { edges: [["orch", "validate"]], post: ["validate"], text: ["ok", "✓ roadmap balances value, risk and modernization"] },
                { edges: [["orch", "browser"]], post: ["browser"], text: ["info", "● empowered teams deliver focused increments"] },
                { edges: [["extract", "review"], ["validate", "review"], ["browser", "review"]], post: ["review"], text: ["ok", "✓ quality gates protect reliability and maintainability"] },
                { edges: [["review", "erp"], ["review", "notify"], ["review", "human"]], post: ["erp", "notify", "human"], text: ["ok", "✓ release, telemetry and feedback guide the next cycle"] }
            ]
        };
        var NODE_W = 92, NODE_H = 36, V_NODE_W = 96, V_NODE_H = 34;

        var flowHTML = function (layout) {
            var vertical = layout === "v";
            var byId = {};
            var nodes = FLOW.nodes.map(function (n) {
                var c = vertical ? n.v : n.h;
                var w = vertical ? Math.min(n.w || V_NODE_W, 104) : (n.w || NODE_W);
                var h = vertical ? V_NODE_H : NODE_H;
                return (byId[n.id] = { id: n.id, label: n.label, cls: n.cls || "", x: c[0], y: c[1], w: w, h: h });
            });
            var edges = FLOW.edges.map(function (e) {
                var a = byId[e[0]], b = byId[e[1]], d;
                if (vertical) {
                    var y1 = a.y + a.h / 2, y2 = b.y - b.h / 2, dy = (y2 - y1) / 2;
                    d = "M" + a.x + " " + y1 + " C" + a.x + " " + (y1 + dy) + " " + b.x + " " + (y2 - dy) + " " + b.x + " " + y2;
                } else {
                    var x1 = a.x + a.w / 2, x2 = b.x - b.w / 2, dx = (x2 - x1) / 2;
                    d = "M" + x1 + " " + a.y + " C" + (x1 + dx) + " " + a.y + " " + (x2 - dx) + " " + b.y + " " + x2 + " " + b.y;
                }
                return '<path class="f-edge' + (e[2] ? " " + e[2] : "") + '" data-edge="' + e[0] + ">" + e[1] + '" d="' + d + '"/>';
            });
            var cols = vertical ? "" : FLOW.cols.map(function (c) { return '<text class="f-col" x="' + c[1] + '" y="22">' + c[0] + "</text>"; }).join("");
            var boxes = nodes.map(function (n) {
                return '<g class="f-node ' + n.cls + '" data-node="' + n.id + '"><rect x="' + (n.x - n.w / 2) + '" y="' + (n.y - n.h / 2) + '" width="' + n.w + '" height="' + n.h + '" rx="10"/><text x="' + n.x + '" y="' + n.y + '">' + n.label + "</text></g>";
            }).join("");
            var vb = vertical ? "0 0 340 392" : "0 0 720 230";
            return '<svg class="' + layout + '" viewBox="' + vb + '">' + cols + edges.join("") + '<g class="f-packets"></g>' + boxes + "</svg>";
        };

        var termCode = termTile.querySelector("[data-term]");
        var termTitle = termTile.querySelector("[data-term-title]");
        var termTag = termTile.querySelector("[data-term-tag]");
        var stepper = termTile.querySelector(".stepper");
        var body = termTile.querySelector(".term-body");
        var flowSvg = termTile.querySelector(".flow-svg");
        var flowCaption = termTile.querySelector(".flow-caption");
        var esc = function (t) { return t.replace(/&/g, "&amp;").replace(/</g, "&lt;"); };
        var wait = function (ms) { return new Promise(function (r) { setTimeout(r, ms); }); };
        var cursor = document.createElement("span");
        cursor.className = "cursor";
        var flowLayout = function () { return body.clientWidth < 560 ? "v" : "h"; };

        var setStep = function (n) {
            $$("li", stepper).forEach(function (li, i) {
                li.classList.toggle("done", i < n);
                li.classList.toggle("active", i === n);
            });
        };
        var setHeader = function (sc) {
            termTitle.textContent = sc.title;
            termTag.textContent = sc.tag;
            stepper.innerHTML = sc.steps.map(function (s) { return "<li>" + s + "</li>"; }).join("");
            setStep(-1);
        };
        var linesHtml = function (sc) {
            return sc.lines.map(function (l) {
                return l.cmd
                    ? '<span class="ln pending"><span class="prompt">$ </span><span class="cmd"><span class="typed"></span><span class="rest">' + esc(l.cmd) + "</span></span></span>"
                    : '<span class="ln pending"><span class="' + l.k + '">  ' + esc(l.out) + "</span></span>";
            }).join("");
        };

        // Reserve the height of the tallest scene so switching never shifts the page
        var reserve = function () {
            var probe = body.cloneNode(true);
            probe.style.cssText = "position:absolute;left:0;right:0;visibility:hidden;min-height:0";
            termTile.appendChild(probe);
            var pre = probe.querySelector(".term"), code = probe.querySelector("code"), flow = probe.querySelector(".flow");
            var max = 0;
            pre.style.display = "block"; flow.style.display = "none";
            scenarios.forEach(function (sc) {
                if (sc.type !== "term") return;
                code.innerHTML = linesHtml(sc);
                max = Math.max(max, probe.offsetHeight);
            });
            pre.style.display = "none"; flow.style.display = "block";
            probe.querySelector(".flow-svg").innerHTML = flowHTML(flowLayout());
            probe.querySelector(".flow-caption").textContent = "x";
            max = Math.max(max, probe.offsetHeight);
            probe.remove();
            body.style.minHeight = max + "px";
        };

        // --- Terminal scene: every line is laid out up front (hidden) and revealed in turn
        var runTerm = function (sc) {
            termTile.classList.remove("show-flow");
            setHeader(sc);
            termCode.innerHTML = linesHtml(sc);
            var lines = $$(".ln", termCode);
            var i = 0;
            var next = function () {
                if (i >= sc.lines.length) {
                    setStep(sc.steps.length);
                    lines[lines.length - 1].appendChild(cursor);
                    return Promise.resolve();
                }
                var l = sc.lines[i], el = lines[i];
                i++;
                if (l.step !== undefined) setStep(l.step);
                el.classList.remove("pending");
                if (!l.cmd) return wait(650).then(next);
                var typed = el.querySelector(".typed"), rest = el.querySelector(".rest");
                typed.after(cursor);
                var c = 0;
                var type = function () {
                    if (c < l.cmd.length) {
                        c++;
                        typed.textContent = l.cmd.slice(0, c);
                        rest.textContent = l.cmd.slice(c);
                        return wait(38 + Math.random() * 40).then(type);
                    }
                    return wait(420).then(next);
                };
                return wait(300).then(type);
            };
            return next();
        };

        // --- Flow scene: nodes light up and data packets travel along the edges
        var sendPackets = function (paths, group) {
            return new Promise(function (resolve) {
                var packets = [];
                paths.forEach(function (path) {
                    var len = path.getTotalLength();
                    [0, 220].forEach(function (delay) {
                        var dot = document.createElementNS("http://www.w3.org/2000/svg", "circle");
                        dot.setAttribute("r", "4");
                        dot.setAttribute("class", "f-packet" + (path.classList.contains("warn") ? " warn" : ""));
                        dot.setAttribute("opacity", "0");
                        group.appendChild(dot);
                        packets.push({ dot: dot, path: path, len: len, delay: delay });
                    });
                });
                var duration = 1100, start = null;
                var frame = function (ts) {
                    if (!start) start = ts;
                    var done = true;
                    packets.forEach(function (p) {
                        var t = Math.min(Math.max((ts - start - p.delay) / duration, 0), 1);
                        if (t < 1) done = false;
                        var e = t < 0.5 ? 2 * t * t : 1 - Math.pow(-2 * t + 2, 2) / 2;
                        var pt = p.path.getPointAtLength(p.len * e);
                        p.dot.setAttribute("cx", pt.x);
                        p.dot.setAttribute("cy", pt.y);
                        p.dot.setAttribute("opacity", t > 0 && t < 1 ? "1" : "0");
                    });
                    if (done) { packets.forEach(function (p) { p.dot.remove(); }); resolve(); }
                    else requestAnimationFrame(frame);
                };
                requestAnimationFrame(frame);
            });
        };

        var runFlow = function (sc) {
            setHeader(sc);
            flowSvg.innerHTML = flowHTML(flowLayout());
            flowCaption.innerHTML = "";
            termTile.classList.add("show-flow");
            var svg = flowSvg.firstChild, packets = svg.querySelector(".f-packets");
            var node = function (id) { return svg.querySelector('[data-node="' + id + '"]'); };
            var edge = function (e) { return svg.querySelector('[data-edge="' + e[0] + ">" + e[1] + '"]'); };
            var lit = [];
            var light = function (ids) {
                lit.forEach(function (n) { n.classList.remove("active"); n.classList.add("done"); });
                lit = ids.map(node);
                lit.forEach(function (n) { n.classList.add("active"); });
            };
            var s = 0;
            var step = function () {
                if (s >= FLOW.steps.length) {
                    setStep(sc.steps.length);
                    lit.forEach(function (n) { n.classList.remove("active"); n.classList.add("done"); });
                    return Promise.resolve();
                }
                var st = FLOW.steps[s];
                setStep(s);
                s++;
                flowCaption.innerHTML = '<span class="' + st.text[0] + '">' + st.text[1] + "</span>";
                if (st.pre) light(st.pre);
                var paths = st.edges.map(edge);
                return wait(350).then(function () {
                    paths.forEach(function (p) { p.classList.add("flowing"); });
                    return sendPackets(paths, packets);
                }).then(function () {
                    paths.forEach(function (p) { p.classList.remove("flowing"); p.classList.add("done"); });
                    light(st.post);
                    return wait(900);
                }).then(step);
            };
            return step();
        };

        // --- Loop through the scenes with a fade between them
        var loop = function (si) {
            var sc = scenarios[si];
            (sc.type === "flow" ? runFlow(sc) : runTerm(sc)).then(function () {
                return wait(4000);
            }).then(function () {
                termTile.classList.add("switching");
                return wait(500);
            }).then(function () {
                loop((si + 1) % scenarios.length);
                termTile.classList.remove("switching");
            });
        };

        reserve();
        var resizeTimer;
        window.addEventListener("resize", function () {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(reserve, 200);
        });

        if (reduceMotion) {
            setHeader(scenarios[0]);
            termCode.innerHTML = linesHtml(scenarios[0]);
            $$(".ln", termCode).forEach(function (el) { el.classList.remove("pending"); });
            $$(".typed", termCode).forEach(function (t) { t.textContent = t.nextSibling.textContent; t.nextSibling.textContent = ""; });
            setStep(scenarios[0].steps.length);
        } else if ("IntersectionObserver" in window) {
            setHeader(scenarios[0]);
            termCode.innerHTML = linesHtml(scenarios[0]);
            var termObs = new IntersectionObserver(function (entries) {
                if (entries[0].isIntersecting) { termObs.disconnect(); setTimeout(function () { loop(0); }, 600); }
            }, { threshold: 0.3 });
            termObs.observe(termTile);
        } else {
            loop(0);
        }
    }

    // Cursor-driven effects (desktop only)
    if (finePointer && !reduceMotion) {
        // Glow: every card in a group tracks the cursor, so borders light up as you approach
        $$(".glow-group").forEach(function (group) {
            var cards = group.classList.contains("card") ? [group] : $$(".card", group);
            var raf = 0, ev = null;
            group.addEventListener("pointermove", function (e) {
                ev = e;
                if (raf) return;
                raf = requestAnimationFrame(function () {
                    raf = 0;
                    cards.forEach(function (c) {
                        var b = c.getBoundingClientRect();
                        c.style.setProperty("--mx", (ev.clientX - b.left) + "px");
                        c.style.setProperty("--my", (ev.clientY - b.top) + "px");
                    });
                });
            });
        });

        // Subtle 3D tilt on hero tiles
        $$(".tilt").forEach(function (tile) {
            tile.addEventListener("pointermove", function (e) {
                var b = tile.getBoundingClientRect();
                var px = (e.clientX - b.left) / b.width - 0.5;
                var py = (e.clientY - b.top) / b.height - 0.5;
                var max = tile.classList.contains("t-intro") ? 2.5 : 6;
                tile.style.transform = "perspective(900px) rotateX(" + (-py * max) + "deg) rotateY(" + (px * max) + "deg)";
            });
            tile.addEventListener("pointerleave", function () { tile.style.transform = ""; });
        });

        // Magnetic buttons
        $$(".magnetic").forEach(function (el) {
            el.addEventListener("pointermove", function (e) {
                var b = el.getBoundingClientRect();
                var dx = e.clientX - (b.left + b.width / 2);
                var dy = e.clientY - (b.top + b.height / 2);
                el.style.translate = (dx * 0.25) + "px " + (dy * 0.35) + "px";
            });
            el.addEventListener("pointerleave", function () { el.style.translate = ""; });
        });
    }

    if (!("IntersectionObserver" in window)) {
        $$(".reveal").forEach(function (el) { el.classList.add("visible", "settled"); });
        $$(".split").forEach(function (el) { el.classList.add("in"); });
        return;
    }

    // Reveal on scroll
    var revealer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (!entry.isIntersecting) return;
            var el = entry.target;
            el.classList.add("visible");
            revealer.unobserve(el);
            // drop the stagger delay afterwards so hover effects respond instantly
            setTimeout(function () { el.classList.add("settled"); }, 1400);
        });
    }, { threshold: 0.12, rootMargin: "0px 0px -40px 0px" });
    $$(".reveal").forEach(function (el) { revealer.observe(el); });

    // Heading word rise
    var splitter = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add("in");
                splitter.unobserve(entry.target);
            }
        });
    }, { threshold: 0.4 });
    $$(".split").forEach(function (el) { splitter.observe(el); });

    // Count-up stats
    var counter = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (!entry.isIntersecting) return;
            var el = entry.target;
            counter.unobserve(el);
            if (reduceMotion) return;
            var target = +el.dataset.count, suffix = el.dataset.suffix || "", start = null;
            function step(ts) {
                if (!start) start = ts;
                var p = Math.min((ts - start) / 1600, 1);
                el.textContent = Math.round(target * (1 - Math.pow(1 - p, 3))) + suffix;
                if (p < 1) requestAnimationFrame(step);
            }
            requestAnimationFrame(step);
        });
    }, { threshold: 0.6 });
    $$("[data-count]").forEach(function (el) { counter.observe(el); });

    // Highlight the nav link for the section in view
    var links = {};
    $$(".nav-links a").forEach(function (a) { links[a.hash.slice(1)] = a; });
    var spy = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (!entry.isIntersecting) return;
            Object.keys(links).forEach(function (k) { links[k].classList.remove("active"); });
            var link = links[entry.target.id];
            if (link) link.classList.add("active");
        });
    }, { rootMargin: "-45% 0px -50% 0px" });
    Object.keys(links).concat(["top", "testimonials"]).forEach(function (id) {
        var section = document.getElementById(id);
        if (section) spy.observe(section);
    });
})();
