//
//  RoomViewModel.swift
//  Streamly
//
//  Created by Aditya Joshi on 01/10/21.
//  Copyright © 2021 Rohit Singh. All rights reserved.
//

import Foundation

protocol RoomViewModelDelegate: AnyObject {
    func apiSuccess()
    func apiFailure(_ message: String,_ status: Int)
    func reloadDataChange()
}

class RoomViewModel: NSObject {
    
    var listOfRoom = [Room]()
    var filtterdRoomList = [Room]()
    var filterString = "" {
        didSet {
            // Return if the filter string hasn't changed.
            guard filterString != oldValue else { return }
            
            // Apply the filter or show all items if the filter string is empty.
            if filterString.isEmpty {
                filtterdRoomList = listOfRoom
            }
            else {
                filtterdRoomList = listOfRoom.filter { ($0.hotel_room?.localizedStandardContains(filterString) ?? false) }
            }
            
            // Reload the collection view to reflect the changes.
            delegate?.reloadDataChange()
        }
    }
    weak var delegate: RoomViewModelDelegate?
    
    
    func getRooms() {
        ApiCall.getSessionRoom { [weak self] data, status in
            guard let weak = self else { return }
            if let data: RoomAPIReponse = DataParser.parseReponseToModel(data!) {
                weak.listOfRoom = data.data ?? []
                weak.filtterdRoomList = data.data ?? []
            }
            weak.delegate?.reloadDataChange()
        } failure: { [weak self] error, data, status in
            guard let weak = self else { return }
            if let `data` = data, let failedData: FailedResponse = DataParser.parseReponseToModel(data) , failedData.response_code == 401 {
                Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isLogin)
                Utility.logOut()
                Switcher.updateRootVC()
                return
            }
            if let data: RoomAPIReponse = DataParser.parseReponseToModel(data!) {
                weak.delegate?.apiFailure(data.message ?? "", data.responseCode ?? 0)
            }
        }
        
    }
    
    func selectRoomToLogin(_ id: String) {
        ApiCall.syncSessionWithRoom(id: id) { [weak self] data, status in
            guard let weak = self else { return }
            Utility.setBoolInUserDefaultData(value: true, key: Constant.UserDefault.isRoomSelected)
            weak.delegate?.apiSuccess()
        } failure: {[weak self] error, data, status in
            guard let weak = self else { return }
            if let `data` = data, let failedData: FailedResponse = DataParser.parseReponseToModel(data) , failedData.response_code == 401 {
                Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isLogin)
                Utility.logOut()
                Switcher.updateRootVC()
                return
            }
            if let data: RoomAPIReponse = DataParser.parseReponseToModel(data!) {
                weak.delegate?.apiFailure(data.message ?? "",data.responseCode ?? 0)
            }
        }

    }
}
