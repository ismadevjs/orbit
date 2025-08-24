@props([
    'action' => 'create', // create, edit, or delete
    'carousel' => null,
    'route' => '',
    'method' => 'POST',
    'modalId' => 'carousel-modal',
    'title' => 'Add New Carousel',
    'submitButtonText' => 'Save',
    'cancelButtonText' => 'Cancel'
])

<div data-tw-backdrop=""
     class="modal group bg-black/60 transition-[visibility,opacity] w-screen h-screen fixed left-0 top-0 [&:not(.show)]:duration-[0s,0.2s] [&:not(.show)]:delay-[0.2s,0s] [&:not(.show)]:invisible [&:not(.show)]:opacity-0 [&.show]:visible [&.show]:opacity-100 [&.show]:duration-[0s,0.4s]"
     id="{{ $modalId }}">
    <div
        class="box relative before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:z-[-1] after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:z-[-1] after:backdrop-blur-md before:bg-background/60 dark:before:shadow-background before:shadow-foreground/60 z-50 mx-auto -mt-16 p-6 transition-[margin-top,transform] duration-[0.4s,0.3s] before:rounded-3xl before:shadow-2xl after:rounded-3xl group-[.show]:mt-16 group-[.modal-static]:scale-[1.05] sm:max-w-lg">
        <div class="p-5">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-medium text-foreground">{{ $title }}</h2>
                <button data-tw-dismiss="modal" type="button" class="flex items-center">
                    <i data-lucide="x" class="size-6 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 text-foreground"></i>
                </button>
            </div>
            @if($action === 'delete')
                <div class="text-center">
                    <i data-lucide="circle-x" class="[--color:currentColor] stroke-(--color) fill-(--color)/25 text-danger mx-auto mt-3 size-16 stroke-1"></i>
                    <div class="mt-5 text-2xl font-medium">Are you sure?</div>
                    <div class="mt-2 opacity-70">
                        Do you really want to delete this carousel? <br>
                        This process cannot be undone.
                    </div>
                </div>
                <div class="px-5 pb-8 text-center flex justify-center gap-2 mt-6">
                    <button
                        type="button"
                        data-tw-dismiss="modal"
                        class="[--color:var(--color-foreground)] cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 text-(--color) hover:bg-(--color)/5 bg-background border-(--color)/20 h-10 px-4 py-2 w-24"
                    >
                        No
                    </button>
                    <form action="{{ $route }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button
                            type="submit"
                            class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 bg-(--color)/20 border-(--color)/60 text-(--color) hover:bg-(--color)/5 [--color:var(--color-danger)] h-10 px-4 py-2 w-24"
                        >
                            Yes
                        </button>
                    </form>
                </div>
            @else
                <form action="{{ $route }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if($action === 'edit')
                        @method('POST')
                        <input type="hidden" name="id" value="{{ $carousel->id }}">
                    @endif
                    <div class="mt-6">
                        <label for="carousel-image" class="block text-sm font-medium text-foreground/70">Carousel Image</label>
                        <div class="mt-2">
                            <input
                                id="carousel-image"
                                name="image"
                                type="file"
                                accept="image/*"
                                class="h-10 rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 box w-full"
                                {{ $action === 'edit' ? '' : 'required' }}
                            >
                            @if($action === 'edit' && $carousel && $carousel->image)
                                <div class="mt-2 flex items-center">
                                    <span class="tooltip border-(--color)/5 block relative size-11 flex-none overflow-hidden rounded-full border-3 ring-1 ring-(--color)/25 [--color:var(--color-primary)] bg-background">
                                        <img class="absolute top-0 size-full object-cover" src="/storage/{{ $carousel->image }}" alt="Carousel Image">
                                    </span>
                                    <span class="ml-2 text-sm text-foreground/70">Current Image</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="mt-4">
                        <label for="carousel-title" class="block text-sm font-medium text-foreground/70">Title</label>
                        <div class="mt-2">
                            <input
                                id="carousel-title"
                                name="title"
                                type="text"
                                placeholder="Enter carousel title"
                                value="{{ $carousel->title ?? '' }}"
                                class="h-10 rounded-md border bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 box w-full"
                                required
                            >
                        </div>
                    </div>
                    <div class="mt-4">
                        <label for="carousel-status" class="block text-sm font-medium text-foreground/70">Status</label>
                        <div class="mt-2">
                            <select
                                id="carousel-status"
                                name="status"
                                class="bg-(image:--background-image-chevron) bg-[position:calc(100%-theme(spacing.3))_center] bg-[size:theme(spacing.5)] bg-no-repeat relative appearance-none flex h-10 rounded-md border bg-background px-3 py-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 box w-full"
                            >
                                <option value="active" {{ $carousel && $carousel->status === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $carousel && $carousel->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-2">
                        <button
                            type="button"
                            data-tw-dismiss="modal"
                            class="[--color:var(--color-foreground)] cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 text-(--color) hover:bg-(--color)/5 bg-background border-(--color)/20 h-10 px-4 py-2 w-24"
                        >
                            {{ $cancelButtonText }}
                        </button>
                        <button
                            type="submit"
                            class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 bg-(--color)/20 border-(--color)/60 text-(--color) hover:bg-(--color)/5 [--color:var(--color-primary)] h-10 px-4 py-2 w-24"
                        >
                            {{ $submitButtonText }}
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
