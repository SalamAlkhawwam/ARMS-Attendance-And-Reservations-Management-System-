<?php

namespace App\Services;

use App\Models\Group;
use App\Models\Invitation;
use App\Models\JoinRequest;
use App\Models\NormalUser;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JoinRequestService
{
    public function showSentJoinRequests()
    {
        $userID = Auth::id();

        $joinRequests = JoinRequest::where('senderID', $userID)
            ->with(['group.service.parentService', 'group.teamMembers.normalUser.user'])
            ->get();

        return response()->json($joinRequests->map(function($joinRequest) {
            return [
                'joinRequestID'=>$joinRequest->id,
                'group_members' => $joinRequest->group->teamMembers->map(function($member) {
                    return $member->normalUser->user->fullName;
                }),
                'service_name' => $joinRequest->group->service->serviceName,
                'parent_service_name' => $joinRequest->group->service->parentService ? $joinRequest->group->service->parentService->serviceName : null,
                'request_date' => $joinRequest->requestDate,
                'status' => $joinRequest->joiningRequestStatus,
            ];
        }));
    }

    public function showReceivedJoinRequests(){

        $user=Auth::user();

        $normalUser = $user->normalUser;

        $groupIDs = TeamMember::where('normalUserID', $normalUser->id )->pluck('groupID');

        $joinRequests = JoinRequest::whereIn('groupID', $groupIDs)
            ->with(['sender', 'group.service.parentService'])->get();

        return response()->json($joinRequests->map(function($joinRequest) {
            return [
                'joinRequestID'=>$joinRequest->id,
                'sender_name' => $joinRequest->sender->fullName,
                'service_name' => $joinRequest->group->service->serviceName,
                'parent_service_name' => $joinRequest->group->service->parentService ? $joinRequest->group->service->parentService->serviceName : null,
                'request_date' => $joinRequest->requestDate,
                'status' => $joinRequest->joiningRequestStatus,
            ];
        }));
    }

    public function sendJoinRequest(Group $group)
    {
        $userID = Auth::id();

        $service = $group->service;

        if ($group->teamMembers()->count() >= $service->maximumNumberOfGroupMembers) {

            return ['message' => 'Group has reached the maximum number of members'];
        }
        $existingJoinRequest = JoinRequest::where('senderID', $userID)->where('groupID', $group->id)->first();

        if ($existingJoinRequest) {

            return ['message' => 'A joining request has already been sent'];
        }
        $joinRequest = JoinRequest::create([
            'senderID' => $userID,
            'groupID' => $group->id,
            'requestDate' => Carbon::today(),
            'joiningRequestStatus' => 'pending',
        ]);
        return ['message' => 'Join Request Sent Successfully', 'join_request' => $joinRequest];
    }

    public function acceptJoinRequest(JoinRequest $joinRequest)
    {
        DB::beginTransaction();
        try {
            if ($joinRequest->joiningRequestStatus !== 'pending') {
                return response()->json(['message' => 'This join request cannot be updated'], 400);
            }
            $group = Group::findOrFail($joinRequest->groupID);
            $service = $group->service;

            if ($group->teamMembers()->count() >= $service->maximumNumberOfGroupMembers) {
                return response()->json(['message' => 'Group has reached the maximum number of members'], 400);
            }


            $user = User::findOrFail($joinRequest->senderID);
            $normalUser = $user->normalUser;

            TeamMember::create([
                'normalUserID' => $normalUser->id,
                'groupID' => $joinRequest->groupID,
            ]);

            JoinRequest::where('senderID', $joinRequest->senderID)
                ->whereHas('group', function ($query) use ($group) {
                    $query->where('serviceID', $group->serviceID);
                })->update(['joiningRequestStatus' => 'cancelled']);
            $joinRequest->update(['joiningRequestStatus' => 'accepted']);


            DB::commit();

            return response()->json(['message' => 'Joining request accepted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function declineJoinRequest(JoinRequest $joinRequest)
    {
        if ($joinRequest->joiningRequestStatus !== 'pending') {

            return ['message' => 'This join request cannot be updated'];
        }
        $joinRequest->update([
            'joiningRequestStatus'=>'declined'
        ]);
        return ['message'=>'joining request declined successfully',];
    }

    public function cancelJoinRequest(JoinRequest $joinRequest)
    {
        $joinRequest->delete();

        return ['message'=>'joining request canceled successfully'];
    }

    public function showSentFromUserToGroup()
    {
        $user = Auth::user();

        $sentRequests = JoinRequest::where('senderID', $user->id)
            ->with([
                'group.service',
                'group.service.parentService',
                'normalUser.user',
                'group.teamMembers.normalUser.user'
            ])
            ->get();

        $result = $sentRequests->map(function ($request) {
            return [
                'serviceName' => $request->group->service->serviceName,
                'parentServiceName' => $request->group->service->parentService ? $request->group->service->parentService->serviceName : null,
                'requestDate' => $request->requestDate,
                'teamMemberNames' => $request->group->teamMembers->map(function ($member) {
                    return $member->normalUser->user->fullName;
                })->toArray()
            ];
        });
        return ['sentJoinRequests' => $result];
    }

    public function showSentFromGroupToUser()
    {
        $user = Auth::user();

        $sentInvitations = JoinRequest::whereHas('group.teamMembers', function ($query) use ($user) {
            $query->where('normalUserID', $user->normalUser->id);
        })->with(['group.service', 'group.service.parentService', 'normalUser.user', 'group.teamMembers.normalUser.user'])
            ->get();

        $result = $sentInvitations->map(function ($invitation) {
            return [
                'senderName' => $invitation->normalUser->user->fullName,
                'teamMemberNames' => $invitation->group->teamMembers->map(function ($member) {
                    return $member->normalUser->user->fullName;
                }),
                'serviceName' => $invitation->group->service->serviceName,
                'parentServiceName' => optional($invitation->group->service->parentService)->serviceName,
                'requestDate' => $invitation->requestDate,
            ];
        });
        return ['sentInvitations' => $result];
    }

    public function showReceivedToGroupFromUser()
    {
        $user = Auth::user();

        $receivedRequests = JoinRequest::whereHas('group.teamMembers', function ($query) use ($user) {
            $query->where('normalUserID', $user->normalUser->id);
        })->with(['group.service', 'group.service.parentService', 'normalUser.user'])
            ->get();

        $result = $receivedRequests->map(function ($request) {
            return [
                'senderName' => $request->normalUser->user->fullName,
                'serviceName' => $request->group->service->serviceName,
                'parentServiceName' => optional($request->group->service->parentService)->serviceName,
                'requestDate' => $request->requestDate,
            ];
        });
        return ['receivedJoinRequests' => $result];
    }

    public function showReceivedToUserFromGroup()
    {
        $user = Auth::user();

        $receivedInvitations = JoinRequest::where('normalUserID', $user->normalUser->id)
            ->with(['group.service', 'group.service.parentService', 'sender', 'group.teamMembers.normalUser.user'])
            ->get();

        $result = $receivedInvitations->map(function ($invitation) {
            return [
                'senderName' => $invitation->sender->fullName,
                'teamMemberNames' => $invitation->group->teamMembers->map(function ($member) {
                    return $member->normalUser->user->fullName;
                })->toArray(),
                'serviceName' => $invitation->group->service->serviceName,
                'parentServiceName' => optional($invitation->group->service->parentService)->serviceName,
                'requestDate' => $invitation->requestDate,
            ];
        });
        return ['receivedInvitations' => $result];
    }

    public function create(Group $group)
    {
        $user= Auth::user();

        $existingRequest = JoinRequest::where('senderID', $user->id)
            ->where('groupID', $group->id)
            ->first();

        if ($existingRequest) {

            return ['message' => 'A joining request has already been sent'];
        }
        JoinRequest::create([
            'senderID'=>$user->id,
            'normalUserID'=>$user->normalUser->id,
            'groupID'=>$group->id,
            'requestDate'=>Carbon::today(),
        ]);
        return ['message'=>'joining request sent successfully'];
    }

    public function ask(Service $service,NormalUser $normalUser)
    {
        $user = Auth::user();

        $askingNormalUser=$user->normalUser;

        $group = Group::where('serviceID',$service->id)->whereHas('teamMembers', function ($query) use ($askingNormalUser) {
            $query->where('normalUserID', $askingNormalUser->id);})->first();

        $existingRequest = JoinRequest::where('normalUserID', $normalUser->id)
            ->where('groupID', $group->id)
            ->first();

        if ($existingRequest) {

            return ['message' => 'A joining request has already been sent'];
        }
        JoinRequest::create([
            'senderID'=>$user->id,
            'normalUserID'=>$normalUser->id,
            'groupID'=>$group->id,
            'requestDate'=>Carbon::today(),
        ]);
        return ['message'=>'joining request sent successfully'];
    }
}
