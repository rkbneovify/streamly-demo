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
    
    var viewModel = RoomViewModel()
    
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
    }
    
    
    @objc func menuButtonAction(recognizer:UITapGestureRecognizer) {
        Switcher.updateRootVC()
    }
}
// MARK: UICollectionViewDataSource

extension RoomViewController: UICollectionViewDataSource
{
    
    func numberOfSections(in collectionView: UICollectionView) -> Int {
        return 1
    }
    
    func collectionView(_ collectionView: UICollectionView, numberOfItemsInSection section: Int) -> Int {
        if viewModel.filtterdRoomList.count == 0 {
            self.collectionView.setEmptyMessage("No record's found")
        } else {
            self.collectionView.restore()
        }
        return viewModel.filtterdRoomList.count
        
    }
    
    func collectionView(_ collectionView: UICollectionView, cellForItemAt indexPath: IndexPath) -> UICollectionViewCell {
        // Dequeue a cell from the collection view.
        return collectionView.dequeueReusableCell(withReuseIdentifier: RoomViewCell.identifier, for: indexPath)
    }
    
    
}

extension RoomViewController: UICollectionViewDelegate {
    func collectionView(_ collectionView: UICollectionView, willDisplay cell: UICollectionViewCell, forItemAt indexPath: IndexPath) {
        guard let cell = cell as? RoomViewCell else { fatalError("Expected to display a `RoomViewCell`.") }
        cell.setData(viewModel.listOfRoom[indexPath.item])
        // Configure the cell.
        cellComposer.composeRoomCell(cell, withDataItem: viewModel.listOfRoom[indexPath.item])
    }
    
    func collectionView(_ collectionView: UICollectionView, didSelectItemAt indexPath: IndexPath) {
        if let selectedID = viewModel.filtterdRoomList[indexPath.item].id {
            viewModel.selectRoomToLogin("\(selectedID)")
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
                Switcher.updateRootVC()
            }
        })
        
    }
    
    func apiFailure(_ message: String,_ status: Int) {
        self.removeSpinner()
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
        collectionView.reloadData()
    }
    
    
}
