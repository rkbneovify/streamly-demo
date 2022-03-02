//
//  RoomViewController.swift
//  Streamly
//
//  Created by Aditya Joshi on 01/10/21.
//  Copyright © 2021 Rohit Singh. All rights reserved.
//

import UIKit

class RoomViewController: UIViewController {
    
    @IBOutlet var collectionView: UICollectionView!
    @IBOutlet var logOutButton: GradientBUtton!
    
    var viewModel = RoomViewModel()
    var filterRoomList = [Room]()
    var filterString = "" {
        didSet {
            // Return if the filter string hasn't changed.
            guard filterString != oldValue else { return }

            // Apply the filter or show all items if the filter string is empty.
            if filterString.isEmpty {
                filterRoomList = viewModel.filtterdRoomList
            } else {
                filterRoomList = viewModel.filtterdRoomList.filter({($0.hotel_room?.lowercased().contains(filterString.lowercased()) ?? false) || $0.id == (Int(filterString))})
            }

            // Reload the collection view to reflect the changes.
            collectionView?.reloadData()
        }
    }
    private let cellComposer = DataItemCellComposer()
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        initialSetup()
    }
    
    override func viewWillAppear(_ animated: Bool) {
        super.viewWillAppear(animated)
        viewModel.delegate = self
        viewModel.getRooms()
        
        if Utility.retriveBooleanFromUserDefault(key: Constant.UserDefault.isRoomExist) && !Utility.retriveBooleanFromUserDefault(key: Constant.UserDefault.isRoomSelected) {
            if let vc = appDelegate?.window?.rootViewController as? UINavigationController, vc.viewControllers.first is RoomViewController {
                return
            }
            let menuPressRecognizer = UITapGestureRecognizer()
            menuPressRecognizer.addTarget(self, action: #selector(RoomViewController.menuButtonAction(recognizer:)))
            menuPressRecognizer.allowedPressTypes = [NSNumber(value: UIPress.PressType.menu.rawValue)]
            self.view.addGestureRecognizer(menuPressRecognizer)
        }
    }
    
    override func viewWillDisappear(_ animated: Bool) {
        super.viewWillDisappear(animated)
    }
    
    private func initialSetup() {
        viewModel.filtterdRoomList = viewModel.listOfRoom
        collectionView.register(UINib(nibName: RoomViewCell.identifier, bundle: nil), forCellWithReuseIdentifier: RoomViewCell.identifier)
        self.view.backgroundColor = UIColor(patternImage: UIImage(named: "bacgroundImage")!)
        //    self.collectionView.backgroundView = UIView()
        self.collectionView.reloadData()
        self.collectionView.layoutIfNeeded()
        self.collectionView.layoutSubviews()
        
        self.logOutButton.setBackgroundImage(UIImage.gradientImageWithBounds(bounds: self.logOutButton.bounds, colors: [UIColor.firstGradientColor.cgColor,UIColor.secondGradientColor.cgColor]), for: .focused)
        self.logOutButton.setBackgroundImage(UIImage.gradientImageWithBounds(bounds: self.logOutButton.bounds, colors: [UIColor.firstGradientColor.cgColor,UIColor.secondGradientColor.cgColor]), for: .highlighted)
        self.logOutButton.setTitleColor(UIColor.white, for: .focused)
        self.logOutButton.setTitleColor(UIColor.white, for: .highlighted)
        
        if #available(tvOS 14.0, *) {
            logOutButton.addAction(UIAction(handler: { _ in
                self.viewPopups()
            }), for: .primaryActionTriggered)
        } else {
            // Fallback on earlier versions
            logOutButton.addTarget(self, action: #selector(logOutAction(_:)), for: .primaryActionTriggered)
        }
    }
    
    @objc func logOutAction(_ sender: UIButton) {
        self.viewPopups()
    }
    
    @objc func menuButtonAction(recognizer:UITapGestureRecognizer) {
        if let vc = appDelegate?.window?.rootViewController as? UINavigationController, vc.viewControllers.first is RoomViewController {
            return
        }
        Switcher.updateRootVC()
    }
    
    fileprivate func openUpdateDeviceName(session: String) {
        let vc = UpdateDeviceViewController.instantiateFromAppStoryboard(appStoryboard: .Extended)
        vc.viewModel.personaldeviceid = session
        self.navigationController?.pushViewController(vc, animated: true)
    }
    
    private func viewPopups()
    {
        let popUPViewsVC = PopUPViewsVC.instantiateFromAppStoryboard(appStoryboard: .Extended)
        popUPViewsVC.modalPresentationStyle = .overCurrentContext
        popUPViewsVC.item = 4
        self.present(popUPViewsVC, animated:true, completion:nil)
    }
}
// MARK: UICollectionViewDataSource

extension RoomViewController: UICollectionViewDataSource
{
    
    func numberOfSections(in collectionView: UICollectionView) -> Int {
        return 1
    }
    
    func collectionView(_ collectionView: UICollectionView, numberOfItemsInSection section: Int) -> Int {
        if filterRoomList.count == 0 {
            self.collectionView.setEmptyMessage("No Room Available. Please remove from web.")
        } else {
            self.collectionView.restore()
        }
        return filterRoomList.count
        
    }
    
    func collectionView(_ collectionView: UICollectionView, cellForItemAt indexPath: IndexPath) -> UICollectionViewCell {
        // Dequeue a cell from the collection view.
        return collectionView.dequeueReusableCell(withReuseIdentifier: RoomViewCell.identifier, for: indexPath)
    }
    
    
}

extension RoomViewController: UICollectionViewDelegate {
    func collectionView(_ collectionView: UICollectionView, willDisplay cell: UICollectionViewCell, forItemAt indexPath: IndexPath) {
        guard let cell = cell as? RoomViewCell else { fatalError("Expected to display a `RoomViewCell`.") }
        cell.setData(filterRoomList[indexPath.item])
        // Configure the cell.
        cellComposer.composeRoomCell(cell, withDataItem: filterRoomList[indexPath.item])
    }
    
    func collectionView(_ collectionView: UICollectionView, didSelectItemAt indexPath: IndexPath) {
        
        showAlert(withMessage: "Are you sure to select this room?", setTwoButton: true, setFirstButtonTitle: "Yes", setSecondButtonTitle: "No") { [weak self] _ in
            guard let weak = self else { return }
            if let selectedID = weak.filterRoomList[indexPath.item].id {
                weak.viewModel.selectRoomToLogin("\(selectedID)")
            }
        }
       
        /* let flag = self.geoData?.success ?? false
         guard let item = filtterdChannelList?[(indexPath.item)] else {return}
         present(videoPlaybackViewController, animated: true) {
         self.videoPlaybackViewController.channelObject = item
         self.videoPlaybackViewController.isAllowedZipCode = flag
         self.videoPlaybackViewController.str = self.geoData?.popupmsg ?? ""
         self.videoPlaybackViewController.isfromRcent = false
         self.videoPlaybackViewController.idS = "\(item.id ?? 0)"
         self.videoPlaybackViewController.isPopAllow = self.geoData?.popupmsgtoggle ?? true
         self.videoPlaybackViewController.filterData = self.filtterdChannelList
         self.videoPlaybackViewController.loadAndPlay()*/
        
    }
}

// MARK: - RoomViewModelDelegate
extension RoomViewController: RoomViewModelDelegate {
    func apiSuccess() {
        self.removeSpinner()
        showToast(message: "Successfully added to room/slot", completion: {
            DispatchQueue.main.asyncAfter(deadline: .now() + 0.2) {
                let isShow = Utility.retriveBooleanFromUserDefault(key: Constant.UserDefault.isDeviceNameAdded)
                if !isShow {
//                    Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isDeviceNameAdded)
                    Switcher.updateRootVC()
                } else {
                    self.openUpdateDeviceName(session: Utility.retriveStringObject(key: Constant.UserDefault.mypersonaldeviceid))
                }
            }
        })
        
    }
    
    func apiFailure(_ message: String,_ status: Int) {
        self.removeSpinner()
        if status == 401 {
            showAlert(withMessage: "Your session has expired. You have to log in again to proceed further.",setFirstButtonTitle: "Logout") { _ in
                Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isLogin)
                Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isDeviceNameAdded)
                Utility.logOut()
            }
            return
        }
        if status == 409 {
            showToast(message: message, completion: {
                self.viewModel.getRooms()
            })
            return
        }
        showSimpleAlertWithMessage(message)
    }
    
    func reloadDataChange() {
        self.removeSpinner()
        self.filterRoomList = viewModel.filtterdRoomList
        collectionView.reloadData()
    }
    
    
}

// MARK: - UISearchResultsUpdating
extension RoomViewController: UISearchResultsUpdating {
    func updateSearchResults(for searchController: UISearchController) {
        filterString = searchController.searchBar.text ?? ""
    }
    
    override func didUpdateFocus(in context: UIFocusUpdateContext, with coordinator: UIFocusAnimationCoordinator) {
        if logOutButton.isFocused{
            coordinator.addCoordinatedAnimations({ () -> Void in
               
            }, completion: nil)
        }else {
            coordinator.addCoordinatedAnimations({ () -> Void in
                self.logOutButton.layer.borderColor = UIColor.gray.cgColor
            }, completion: nil)
            }
        }
}
