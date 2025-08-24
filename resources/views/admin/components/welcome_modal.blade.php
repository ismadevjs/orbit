<div data-tw-backdrop=""
                            class="modal group bg-black/60 transition-[visibility,opacity] w-screen h-screen fixed left-0 top-0 [&:not(.show)]:duration-[0s,0.2s] [&:not(.show)]:delay-[0.2s,0s] [&:not(.show)]:invisible [&:not(.show)]:opacity-0 [&.show]:visible [&.show]:opacity-100 [&.show]:duration-[0s,0.4s]"
                            id="onboarding-dialog">
                            <div
                                class="box relative before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:z-[-1] after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:z-[-1] after:backdrop-blur-md before:bg-background/60 dark:before:shadow-background before:shadow-foreground/60 z-50 mx-auto -mt-16 p-6 transition-[margin-top,transform] duration-[0.4s,0.3s] before:rounded-3xl before:shadow-2xl after:rounded-3xl group-[.show]:mt-16 group-[.modal-static]:scale-[1.05] sm:max-w-xl">
                                <a class="bg-background/80 hover:bg-background absolute right-0 top-0 -mr-3 -mt-3 flex size-9 items-center justify-center rounded-full border shadow outline-none backdrop-blur"
                                    data-tw-dismiss="modal" href="#">
                                    <i data-lucide="x"
                                        class="stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 size-5 opacity-70"></i>
                                </a>
                                <div class="-mx-3 pb-5">
                                    <div data-config="{&#10;                        nav: true&#10;                    }"
                                        class="tiny-slider mb-11 mt-2">
                                        <div class="relative mx-3 flex flex-col items-center gap-1 px-3.5">
                                            <div
                                                class="w-full bg-primary/[.05] mb-7 border-primary/10 shadow-lg shadow-black/10 relative rounded-3xl border h-52 overflow-hidden before:bg-noise before:absolute before:inset-0 before:opacity-30 after:bg-accent after:absolute after:inset-0 after:opacity-30 after:blur-2xl">
                                                <img class="absolute inset-0 mx-auto mt-10 w-2/5 scale-125"
                                                    src="{{asset('dist/images/phone-illustration.svg')}}"
                                                    alt="Midone - Tailwind Admin Dashboard Template">
                                            </div>
                                            <div class="px-8">
                                                <div class="text-center text-xl font-medium">Welcome to Midone Admin!
                                                </div>
                                                <div class="mt-3 text-center text-base leading-relaxed opacity-70">
                                                    Premium admin dashboard template for all kinds <br> of projects.
                                                    With a unique and modern design, Midone offers the perfect
                                                    foundation to build professional
                                                    applications with ease.
                                                </div>
                                            </div>
                                            <div
                                                class="absolute inset-x-0 bottom-0 -mb-12 flex place-content-between px-5">
                                                <a class="text-danger flex items-center gap-3 font-medium"
                                                    data-tw-dismiss="modal" href="">
                                                    Skip Intro
                                                </a>
                                                <a class="text-primary flex items-center gap-3 font-medium"
                                                    href="">
                                                    Next <i data-lucide="move-right"
                                                        class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="relative mx-3 flex flex-col items-center gap-1 px-3.5">
                                            <div
                                                class="w-full bg-primary/[.05] mb-7 border-primary/10 shadow-lg shadow-black/10 relative rounded-3xl border h-52 overflow-hidden before:bg-noise before:absolute before:inset-0 before:opacity-30 after:bg-accent after:absolute after:inset-0 after:opacity-30 after:blur-2xl">
                                                <img class="absolute inset-0 mx-auto mt-10 w-2/5 scale-125"
                                                    src="{{asset('dist/images/woman-illustration.svg')}}"
                                                    alt="Midone - Tailwind Admin Dashboard Template">
                                            </div>
                                            <div class="w-full">
                                                <div class="text-center text-xl font-medium">Example Request
                                                    Information</div>
                                                <div class="mt-3 text-center text-base leading-relaxed opacity-70">
                                                    Your premium admin dashboard template.
                                                </div>
                                                <div class="mt-8">
                                                    <div class="grid grid-cols-2 gap-5 px-5">
                                                        <div class="flex flex-col gap-2.5"><label
                                                                class="font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Full
                                                                Name</label>
                                                            <input type="text" placeholder="John Doe"
                                                                class="h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                                                        </div>
                                                        <div class="flex flex-col gap-2.5"><label
                                                                class="font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Event</label>
                                                            <select
                                                                class="bg-(image:--background-image-chevron) bg-[position:calc(100%-theme(spacing.3))_center] bg-[size:theme(spacing.5)] bg-no-repeat relative appearance-none flex h-10 w-full rounded-md border bg-background px-3 py-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                                                                <option>Corporate Event</option>
                                                                <option>Wedding</option>
                                                                <option>Birthday</option>
                                                                <option>Other</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                class="absolute inset-x-0 bottom-0 -mb-12 flex place-content-between px-5">
                                                <a class="text-primary flex items-center gap-3 font-medium"
                                                    href="">
                                                    <i data-lucide="move-left"
                                                        class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25"></i>
                                                    Previous
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
